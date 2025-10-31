<script setup>
import { ref, watch, computed } from 'vue'
import { router } from '@inertiajs/vue3'

// shadcn/ui components
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationFirst,
    PaginationItem,
    PaginationLast,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination'
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group'
import { Checkbox } from '@/components/ui/checkbox'
import { Button } from '@/components/ui/button'

import sun from '@/assets/sun.png'

// Fallback image (SVG data URI placeholder)
const placeholderImg =
    'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80"><rect width="100%" height="100%" fill="%23e5e7eb"/><path d="M20 50l10-12 8 9 12-16 10 13" stroke="%239ca3af" stroke-width="3" fill="none"/></svg>'

const props = defineProps({
    searchResults: { type: Object, required: true }, // Spatie Data -> SearchResult
    filters: { type: Object, required: true }, // initial filters from server
})

// Text query
const searchTerm = ref(props.filters.searchTerm || '')

// Single-select facet (radio)
const filterProductType = ref(props.filters.filterProductType || 'all')

// Multi-select facets (checkbox arrays)
const filterManufacturer = ref(
    Array.isArray(props.filters.filterManufacturer)
        ? props.filters.filterManufacturer
        : [],
)
const filterConnectorType = ref(
    Array.isArray(props.filters.filterConnectorType)
        ? props.filters.filterConnectorType
        : [],
)

// Numeric ranges
const filterPriceMin = ref(props.filters.filterPrice?.min ?? '')
const filterPriceMax = ref(props.filters.filterPrice?.max ?? '')

const filterPowerOutputMin = ref(props.filters.filterPowerOutput?.min ?? '')
const filterPowerOutputMax = ref(props.filters.filterPowerOutput?.max ?? '')

const filterCapacityMin = ref(props.filters.filterCapacity?.min ?? '')
const filterCapacityMax = ref(props.filters.filterCapacity?.max ?? '')

// Pagination
const currentPage = ref(props.searchResults.page || 1)

// Facet configs — NOTE: new keys manufacturer_name, connector_type_name
const facetConfigs = {
    type: {
        label: 'Product Type',
        model: filterProductType,
        multi: false, // radio
    },
    manufacturer_name: {
        label: 'Manufacturer',
        model: filterManufacturer,
        multi: true, // checkbox
    },
    connector_type_name: {
        label: 'Connector Type',
        model: filterConnectorType,
        multi: true, // checkbox
    },
}

// Build list of facets from backend merged counts
const availableFacets = computed(() => {
    const raw = props.searchResults?.raw?.facets || {}
    return Object.entries(raw).map(([facet, values]) => ({
        facet,
        label: facetConfigs[facet]?.label || facet,
        model: facetConfigs[facet]?.model,
        multi: !!facetConfigs[facet]?.multi,
        values: Object.entries(values), // [[value, count], ...]
    }))
})

// Hide connector_type facet unless Product Type is 'Connector'
const filteredFacets = computed(() => {
    return availableFacets.value.filter((f) => {
        if (f.facet === 'connector_type_name') {
            return filterProductType.value === 'Connector'
        }
        return true
    })
})

function toggleFacetValue(facetName, value, checked) {
    const cfg = facetConfigs[facetName]
    if (!cfg || !cfg.model || !cfg.multi) return
    const arr = Array.isArray(cfg.model.value) ? [...cfg.model.value] : []
    const idx = arr.indexOf(value)
    if (checked && idx === -1) arr.push(value)
    if (!checked && idx !== -1) arr.splice(idx, 1)
    cfg.model.value = arr
}

function search(page = 1) {
    router.get(
        '/',
        {
            searchTerm: searchTerm.value,
            // radio facet (empty means "All")
            filterProductType:
                filterProductType.value === 'all' ? '' : filterProductType.value,

            // multi facets — arrays (connector only when product type is Connector)
            filterManufacturer: Array.isArray(filterManufacturer.value)
                ? filterManufacturer.value
                : [],
            filterConnectorType:
                filterProductType.value === 'Connector' &&
                Array.isArray(filterConnectorType.value)
                    ? filterConnectorType.value
                    : [],

            // numeric ranges
            filterPrice: {
                min:
                    filterPriceMin.value === '' || filterPriceMin.value === '0'
                        ? null
                        : Number(filterPriceMin.value),
                max:
                    filterPriceMax.value === '' || filterPriceMax.value === '0'
                        ? null
                        : Number(filterPriceMax.value),
            },
            filterPowerOutput: {
                min:
                    filterPowerOutputMin.value === '' ||
                    filterPowerOutputMin.value === '0'
                        ? null
                        : Number(filterPowerOutputMin.value),
                max:
                    filterPowerOutputMax.value === '' ||
                    filterPowerOutputMax.value === '0'
                        ? null
                        : Number(filterPowerOutputMax.value),
            },
            filterCapacity: {
                min:
                    filterCapacityMin.value === '' || filterCapacityMin.value === '0'
                        ? null
                        : Number(filterCapacityMin.value),
                max:
                    filterCapacityMax.value === '' || filterCapacityMax.value === '0'
                        ? null
                        : Number(filterCapacityMax.value),
            },
            page,
        },
        {
            preserveScroll: true,
            preserveState: true,
        },
    )
}

function resetFilters() {
    searchTerm.value = ''
    filterProductType.value = 'all'
    filterManufacturer.value = []
    filterConnectorType.value = []

    filterPriceMin.value = ''
    filterPriceMax.value = ''
    filterPowerOutputMin.value = ''
    filterPowerOutputMax.value = ''
    filterCapacityMin.value = ''
    filterCapacityMax.value = ''
}

watch(currentPage, (newPage) => {
    search(newPage)
})

// When product type changes, clear irrelevant filters
watch(filterProductType, (newVal) => {
    if (newVal !== 'Battery') {
        filterCapacityMin.value = ''
        filterCapacityMax.value = ''
    }
    if (newVal !== 'SolarPanel') {
        filterPowerOutputMin.value = ''
        filterPowerOutputMax.value = ''
    }
    if (newVal !== 'Connector') {
        filterConnectorType.value = []
    }
})

watch(
    [
        searchTerm,
        filterProductType,
        filterManufacturer,
        filterConnectorType,
        filterPriceMin,
        filterPriceMax,
        filterPowerOutputMin,
        filterPowerOutputMax,
        filterCapacityMin,
        filterCapacityMax,
    ],
    () => {
        currentPage.value = 1
        search(1)
    },
)
</script>

<template>
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-2 my-4 cursor-pointer" @click="resetFilters">
            <img :src="sun" alt="Sun icon" class="h-8 w-8" />
            <h1 class="text-2xl font-bold">Sunny Stuff Search</h1>
        </div>

        <div class="grid grid-cols-4 gap-4 p-4">
            <!-- SIDEBAR -->
            <div class="col-span-1">
                <Card>
                    <CardHeader>
                        <CardTitle>Filters</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div>
                            <Label class="mb-2 block">Search</Label>
                            <Input v-model="searchTerm" placeholder="Search products..." />
                        </div>

                        <!-- Dynamic facets using merged disjunctive counts from backend -->
                        <div v-for="facet in filteredFacets" :key="facet.facet" class="space-y-2">
                            <Label>{{ facet.label }}</Label>

                            <!-- RADIO for single-select (type) -->
                            <div v-if="!facet.multi && facet.model">
                                <RadioGroup v-model="facet.model.value">
                                    <div class="flex items-center space-x-2">
                                        <RadioGroupItem :id="facet.facet + '-all'" value="all" />
                                        <Label :for="facet.facet + '-all'">All</Label>
                                    </div>
                                    <div
                                        v-for="([value, count]) in facet.values"
                                        :key="facet.facet + '-' + value"
                                        class="flex items-center space-x-2"
                                    >
                                        <RadioGroupItem
                                            :id="facet.facet + '-' + value"
                                            :value="value"
                                            :disabled="count === 0"
                                        />
                                        <Label :for="facet.facet + '-' + value">{{ value }} ({{ count }})</Label>
                                    </div>
                                </RadioGroup>
                            </div>

                            <!-- CHECKBOX for multi-select (manufacturer_name, connector_type_name) -->
                            <div v-else-if="facet.multi && facet.model" class="space-y-1">
                                <div class="flex items-center space-x-2">
                                    <!-- "All" = empty selection array -->
                                    <Checkbox
                                        :id="facet.facet + '-all'"
                                        :model-value="Array.isArray(facet.model.value) ? facet.model.value.length === 0 : true"
                                        @update:model-value="(checked) => { if (checked) { facet.model.value = [] } }"
                                    />
                                    <Label :for="facet.facet + '-all'">All</Label>
                                </div>

                                <div
                                    v-for="([value, count]) in facet.values"
                                    :key="facet.facet + '-' + value"
                                    class="flex items-center space-x-2"
                                >
                                    <Checkbox
                                        :id="facet.facet + '-' + value"
                                        :model-value="Array.isArray(facet.model.value) && facet.model.value.includes(value)"
                                        :disabled="count === 0 && !(Array.isArray(facet.model.value) && facet.model.value.includes(value))"
                                        @update:model-value="(next) => toggleFacetValue(facet.facet, value, next)"
                                    />
                                    <Label :for="facet.facet + '-' + value">{{ value }} ({{ count }})</Label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <Label>Price</Label>
                            <div class="flex space-x-2">
                                <Input type="number" placeholder="Min" v-model="filterPriceMin" min="0" />
                                <Input type="number" placeholder="Max" v-model="filterPriceMax" min="0" />
                            </div>
                        </div>

                        <div v-if="filterProductType === 'SolarPanel'">
                            <Label>Power Output</Label>
                            <div class="flex space-x-2">
                                <Input type="number" placeholder="Min" v-model="filterPowerOutputMin" min="0" />
                                <Input type="number" placeholder="Max" v-model="filterPowerOutputMax" min="0" />
                            </div>
                        </div>

                        <div v-if="filterProductType === 'Battery'">
                            <Label>Capacity</Label>
                            <div class="flex space-x-2">
                                <Input type="number" placeholder="Min" v-model="filterCapacityMin" min="0" />
                                <Input type="number" placeholder="Max" v-model="filterCapacityMax" min="0" />
                            </div>
                        </div>

                        <div class="pt-2">
                            <Button variant="outline" class="w-full" @click="resetFilters">Reset</Button>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- RESULTS -->
            <div class="col-span-3">
                <Card>
                    <CardHeader>
                        <CardTitle>Results ({{ searchResults.total }})</CardTitle>
                    </CardHeader>

                    <CardContent>
                        <div v-if="searchResults?.hits?.length" class="space-y-4">
                            <div
                                v-for="item in searchResults.hits"
                                :key="item.objectID"
                                class="border rounded-2xl p-4 md:p-5 shadow-sm hover:shadow transition bg-white"
                            >
                                <div class="flex items-start gap-4">
                                    <!-- Thumbnail / placeholder -->
                                    <img
                                        :src="placeholderImg"
                                        alt="Product image"
                                        class="w-20 h-20 rounded-md object-cover flex-shrink-0"
                                    />

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                                            <h3 class="text-lg font-semibold truncate">
                                                {{ item.name || item.title || 'Product' }}
                                            </h3>
                                            <div class="text-sm font-semibold">
                                                Price:
                                                <span class="font-normal">{{ item.price ?? '—' }}</span>
                                            </div>
                                        </div>

                                        <p v-if="item.description" class="text-sm mt-1 text-gray-700 leading-snug">
                                            {{ item.description }}
                                        </p>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1 mt-3 text-sm">
                                            <div>
                                                <span class="font-semibold">Manufacturer:</span>
                                                <span class="ml-1">{{ item.manufacturer_name ?? '—' }}</span>
                                            </div>
                                            <div>
                                                <span class="font-semibold">Type:</span>
                                                <span class="ml-1">{{ item.type ?? '—' }}</span>
                                            </div>
                                            <div v-if="item.connector_type_name">
                                                <span class="font-semibold">Connector:</span>
                                                <span class="ml-1">{{ item.connector_type_name }}</span>
                                            </div>
                                            <div v-if="item.power_output !== undefined && item.power_output !== null">
                                                <span class="font-semibold">Power Output:</span>
                                                <span class="ml-1">{{ item.power_output }}</span>
                                            </div>
                                            <div v-if="item.capacity !== undefined && item.capacity !== null">
                                                <span class="font-semibold">Capacity:</span>
                                                <span class="ml-1">{{ item.capacity }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else class="py-8 text-center opacity-70">No results found.</div>

                        <div class="mt-6" v-if="searchResults.total > 0">
                            <Pagination
                                v-model:page="currentPage"
                                :total="searchResults.total"
                                :items-per-page="searchResults.perPage"
                                :sibling-count="1"
                                show-edges
                                class="mx-auto w-fit"
                            >
                                <PaginationContent v-slot="{ items }" class="flex items-center gap-1">
                                    <PaginationFirst />
                                    <PaginationPrevious />

                                    <template v-for="(pItem, index) in items" :key="index">
                                        <PaginationItem v-if="pItem.type === 'page'" :value="pItem.value" as-child>
                                            <Button
                                                class="w-10 h-10 p-0"
                                                :variant="pItem.value === currentPage ? 'default' : 'outline'"
                                            >
                                                {{ pItem.value }}
                                            </Button>
                                        </PaginationItem>
                                        <PaginationEllipsis v-else :index="index" />
                                    </template>

                                    <PaginationNext />
                                    <PaginationLast />
                                </PaginationContent>
                            </Pagination>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
