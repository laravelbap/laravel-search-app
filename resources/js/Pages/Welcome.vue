<script setup>
import {ref, watch, computed} from 'vue';
import {router} from '@inertiajs/vue3';
import {Card, CardContent, CardHeader, CardTitle} from '@/components/ui/card';
import {Input} from '@/components/ui/input';
import {Label} from '@/components/ui/label';
import {Select, SelectContent, SelectItem, SelectTrigger, SelectValue} from '@/components/ui/select';
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationFirst,
    PaginationItem,
    PaginationLast,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import {Button} from '@/components/ui/button';
import {RadioGroup, RadioGroupItem} from '@/components/ui/radio-group';


import sun from '@/assets/sun.png'

const props = defineProps({
    searchResults: Object,
    filters: Object,
});

const searchTerm = ref(props.filters.searchTerm || '');
const filterProductType = ref(props.filters.filterProductType || null);
const filterManufacturer = ref(props.filters.filterManufacturer || null);
const filterConnectorType = ref(props.filters.filterConnectorType || null);
const filterPriceMin = ref(props.filters.filterPrice?.min || '');
const filterPriceMax = ref(props.filters.filterPrice?.max || '');
const filterPowerOutputMin = ref(props.filters.filterPowerOutput?.min || '');
const filterPowerOutputMax = ref(props.filters.filterPowerOutput?.max || '');
const filterCapacityMin = ref(props.filters.filterCapacity?.min || '');
const filterCapacityMax = ref(props.filters.filterCapacity?.max || '');

const currentPage = ref(props.searchResults.page || 1);

const facetConfigs = {
    type: {
        label: 'Product Type',
        model: filterProductType,
    },
    manufacturer_name: {
        label: 'Manufacturer',
        model: filterManufacturer,
    },
    connector_type_name: {
        label: 'Connector Type',
        model: filterConnectorType,
    },
};

const availableFacets = computed(() => {
    return Object.entries(props.searchResults.raw.facets || {}).map(([facet, values]) => ({
        facet,
        label: facetConfigs[facet]?.label || facet,
        model: facetConfigs[facet]?.model,
        values: Object.entries(values),
    }));
});

const search = (page = 1) => {
    router.get('/', {
        searchTerm: searchTerm.value,
        filterProductType: filterProductType.value === 'all' ? '' : filterProductType.value,
        filterManufacturer: filterManufacturer.value === 'all' ? '' : filterManufacturer.value,
        filterConnectorType: filterConnectorType.value === 'all' ? '' : filterConnectorType.value,
        filterPrice: {
            min: (filterPriceMin.value === '' || filterPriceMin.value === '0') ? null : filterPriceMin.value,
            max: filterPriceMax.value === '' ? null : filterPriceMax.value,
        },
        filterPowerOutput: {
            min: (filterPowerOutputMin.value === '' || filterPowerOutputMin.value === '0') ? null : filterPowerOutputMin.value,
            max: filterPowerOutputMax.value === '' ? null : filterPowerOutputMax.value,
        },
        filterCapacity: {
            min: (filterCapacityMin.value === '' || filterCapacityMin.value === '0') ? null : filterCapacityMin.value,
            max: filterCapacityMax.value === '' ? null : filterCapacityMax.value,
        },
        page,
    }, {preserveState: true, replace: true});
};

const resetFilters = () => {
    router.get('/', {}, {replace: true});
};

watch(filterProductType, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    filterPowerOutputMin.value = '';
    filterPowerOutputMax.value = '';
    filterCapacityMin.value = '';
    filterCapacityMax.value = '';
    filterConnectorType.value = null;
  }
});

watch(currentPage, (newPage) => {
    search(newPage);
});

watch([searchTerm, filterProductType, filterManufacturer, filterConnectorType, filterPriceMin, filterPriceMax, filterPowerOutputMin, filterPowerOutputMax, filterCapacityMin, filterCapacityMax], () => {
    currentPage.value = 1;
    search(1);
});

</script>

<template>
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-2 my-4 cursor-pointer" @click="resetFilters">
            <img :src="sun" alt="Sun icon" class="h-8 w-8"/>
            <h1 class="text-2xl font-bold">Sunny Stuff Search</h1>
        </div>
        <div class="grid grid-cols-4 gap-4 p-4">
            <div class="col-span-1">
                <Card>
                    <CardHeader>
                        <CardTitle>Filters</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <Label for="search">Search</Label>
                            <Input id="search" v-model="searchTerm"/>
                        </div>

                        <div>
                            <Label>Product Type</Label>
                            <RadioGroup v-model="filterProductType" class="flex flex-col space-y-1">
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem id="allProductTypes" value="all"/>
                                    <Label for="allProductTypes">All</Label>
                                </div>
                                <div v-for="([value, count]) in availableFacets.find(f => f.facet === 'type')?.values" :key="value" class="flex items-center space-x-2">
                                    <RadioGroupItem :id="value" :value="value"/>
                                    <Label :for="value">{{ value }} ({{ count }})</Label>
                                </div>
                            </RadioGroup>
                        </div>

                        <!-- Manufacturer Filter (moved here) -->
                        <div v-if="availableFacets.find(f => f.facet === 'manufacturer_name')">
                            <Label>Manufacturer</Label>
                            <RadioGroup v-model="filterManufacturer" class="flex flex-col space-y-1">
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem id="manufacturer-all" value="all"/>
                                    <Label for="manufacturer-all">All</Label>
                                </div>
                                <div v-for="([value, count]) in availableFacets.find(f => f.facet === 'manufacturer_name')?.values" :key="value" class="flex items-center space-x-2">
                                    <RadioGroupItem :id="value" :value="value"/>
                                    <Label :for="value">{{ value }} ({{ count }})</Label>
                                </div>
                            </RadioGroup>
                        </div>


                        <div>
                            <Label>Price</Label>
                            <div class="flex space-x-2">
                                <Input type="number" placeholder="Min" v-model="filterPriceMin" min="0"/>
                                <Input type="number" placeholder="Max" v-model="filterPriceMax" min="0"/>
                            </div>
                        </div>

                        <!-- Connector Type Filter (conditional) -->
                        <div v-if="filterProductType === 'Connector' && availableFacets.find(f => f.facet === 'connector_type_name')">
                            <Label>Connector Type</Label>
                            <RadioGroup v-model="filterConnectorType" class="flex flex-col space-y-1">
                                <div class="flex items-center space-x-2">
                                    <RadioGroupItem id="connector_type_name-all" value="all"/>
                                    <Label for="connector_type_name-all">All</Label>
                                </div>
                                <div v-for="([value, count]) in availableFacets.find(f => f.facet === 'connector_type_name')?.values" :key="value" class="flex items-center space-x-2">
                                    <RadioGroupItem :id="value" :value="value"/>
                                    <Label :for="value">{{ value }} ({{ count }})</Label>
                                </div>
                            </RadioGroup>
                        </div>

                        <!-- Power Output Filter (conditional) -->
                        <div v-if="filterProductType === 'SolarPanel'">
                            <Label>Power Output</Label>
                            <div class="flex space-x-2">
                                <Input type="number" placeholder="Min" v-model="filterPowerOutputMin" min="0"/>
                                <Input type="number" placeholder="Max" v-model="filterPowerOutputMax" min="0"/>
                            </div>
                        </div>

                        <!-- Capacity Filter (conditional) -->
                        <div v-if="filterProductType === 'Battery'">
                            <Label>Capacity</Label>
                            <div class="flex space-x-2">
                                <Input type="number" placeholder="Min" v-model="filterCapacityMin" min="0"/>
                                <Input type="number" placeholder="Max" v-model="filterCapacityMax" min="0"/>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
            <div class="col-span-3">
                <div v-if="searchResults?.hits.length > 0">
                    <div v-for="hit in searchResults?.hits" :key="hit.objectID" class="mb-4">
                        <Card>
                            <CardContent class="flex items-start p-4">
                                <div class="w-24 h-24 bg-gray-200 mr-4 flex-shrink-0"></div>
                                <div class="flex-grow">
                                    <h2 class="text-lg font-bold" v-html="hit.name"></h2>
                                    <p class="text-sm text-gray-600" v-html="hit?._highlightResult?.description?.value"></p>
                                    <div class="mt-2 grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
                                        <div v-if="hit.price">
                                            <strong>Price:</strong> {{ hit.price }}
                                        </div>
                                        <div v-if="hit.power_output">
                                            <strong>Power Output:</strong> {{ hit.power_output }} W
                                        </div>
                                        <div v-if="hit.capacity">
                                            <strong>Capacity:</strong> {{ hit.capacity }} kWh
                                        </div>
                                        <div v-if="hit.type">
                                            <strong>Type:</strong> {{ hit.type }}
                                        </div>
                                        <div v-if="hit.manufacturer_name">
                                            <strong>Manufacturer:</strong> {{ hit.manufacturer_name }}
                                        </div>
                                        <div v-if="hit.connector_type_name">
                                            <strong>Connector:</strong> {{ hit.connector_type_name }}
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>


                    <Pagination
                        v-model:page="currentPage"
                        :total="searchResults.total"
                        :items-per-page="searchResults.perPage"
                        :sibling-count="1"
                        show-edges
                        class="mx-auto w-fit"
                    >
                        <PaginationContent v-slot="{ items }" class="flex items-center gap-1">
                            <PaginationFirst/>
                            <PaginationPrevious/>

                            <template v-for="(item, index) in items">
                                <PaginationItem v-if="item.type === 'page'" :key="index" :value="item.value" as-child>
                                    <Button class="w-10 h-10 p-0" :variant="item.value === currentPage ? 'default' : 'outline'">
                                        {{ item.value }}
                                    </Button>
                                </PaginationItem>
                                <PaginationEllipsis v-else :key="item.type" :index="index"/>
                            </template>

                            <PaginationNext/>
                            <PaginationLast/>
                        </PaginationContent>
                    </Pagination>

                </div>
                <div v-else>
                    <p>No results found.</p>
                </div>
            </div>
        </div>


        <pre>
                {{ searchResults }}
            </pre>
    </div>
</template>
