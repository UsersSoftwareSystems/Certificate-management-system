@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Certificate Management Overview')

@section('content')
<div class="space-y-6">
    <!-- KPI Cards - DYNAMIC FLEX WRAP LAYOUT -->
    <div class="flex flex-wrap gap-4 lg:gap-6">
        <!-- Total Applications -->
        <div class="flex-1 min-w-[200px] xl:min-w-[250px] bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium card-text-secondary h-9">Total Applications</p>
                    <p class="text-2xl font-bold card-text-primary mt-1">{{ number_format($stats['total_applications']) }}</p>
                    <p class="text-green-600 dark:text-green-400 text-xs font-medium mt-1">+12% vs last month</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Applications -->
        <div class="flex-1 min-w-[200px] xl:min-w-[250px] bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium card-text-secondary h-9">Pending Review</p>
                    <p class="text-2xl font-bold card-text-primary mt-1">{{ number_format($stats['pending_applications']) }}</p>
                    <p class="text-yellow-600 dark:text-yellow-400 text-xs font-medium mt-1">{{ $stats['pending_applications'] > 0 ? 'Needs attention' : 'All clear' }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/60 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-500 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Verified Applications -->
        <div class="flex-1 min-w-[200px] xl:min-w-[250px] bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium card-text-secondary h-9">Verified</p>
                    <p class="text-2xl font-bold card-text-primary mt-1">{{ number_format($stats['verified_applications']) }}</p>
                    @php $rate = $stats['total_applications'] ? round(($stats['verified_applications'] / $stats['total_applications']) * 100, 1) : 0; @endphp
                    <p class="text-green-600 dark:text-green-400 text-xs font-medium mt-1">{{ $rate }}% completion</p>
                </div>
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="flex-1 min-w-[200px] xl:min-w-[250px] bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium card-text-secondary h-9">Documents</p>
                    <p class="text-2xl font-bold card-text-primary mt-1">{{ number_format($stats['total_uploads']) }}</p>
                    @php $avg = $stats['total_applications'] ? round($stats['total_uploads'] / $stats['total_applications'], 1) : 0; @endphp
                    <p class="text-purple-600 dark:text-purple-400 text-xs font-medium mt-1">{{ $avg }} avg per app</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Certificates Generated -->
        <div class="flex-1 min-w-[200px] xl:min-w-[250px] bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium card-text-secondary h-9">Certificates Generated</p>
                    <p class="text-2xl font-bold card-text-primary mt-1">{{ number_format($stats['certificates_generated']) }}</p>
                    @php
                        $avgCertificates = $stats['total_applications'] > 0 
                            ? round($stats['certificates_generated'] / $stats['total_applications'], 1)
                            : 0;
                    @endphp
                    <p class="text-indigo-600 dark:text-indigo-400 text-xs font-medium mt-1">{{ $avgCertificates }} per application</p>
                </div>
                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section - Side-by-Side Layout -->
    <div class="w-full">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Line Chart - Left Side -->
            <div class="flex-1 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold card-text-primary">Application Trends</h3>
                    <p class="card-text-secondary text-sm">Monthly submissions over the last 12 months</p>
                </div>
                <div style="height: 300px; position: relative;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <!-- Donut Chart - Right Side -->
            <div class="flex-1 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold card-text-primary">Application Status</h3>
                    <p class="card-text-secondary text-sm">Application status breakdown</p>
                </div>
                
                <!-- Chart centered -->
                <div class="flex justify-center mb-6">
                    <div style="height: 200px; width: 200px; position: relative;">
                        <canvas id="applicationStatusChart"></canvas>
                    </div>
                </div>

                <!-- Legend Below Chart -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center">
                            <div class="legend-dot-pending"></div>
                            <span class="text-sm font-medium card-text-primary">Pending</span>
                        </div>
                        <span class="text-sm font-bold card-text-primary">{{ $applicationStatusStats['pending'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center">
                            <div class="legend-dot-verified"></div>
                            <span class="text-sm font-medium card-text-primary">Verified</span>
                        </div>
                        <span class="text-sm font-bold card-text-primary">{{ $applicationStatusStats['verified'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center">
                            <div class="legend-dot-rejected"></div>
                            <span class="text-sm font-medium card-text-primary">Rejected</span>
                        </div>
                        <span class="text-sm font-bold card-text-primary">{{ $applicationStatusStats['rejected'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Applications Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold card-text-primary">Recent Applications</h3>
                <p class="card-text-secondary text-sm">Latest certificate applications submitted</p>
            </div>
            <a 
                href="{{ route('admin.applicants.index') }}" 
                @click.stop 
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors"
            >
                View All
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="text-left py-3 px-4 text-sm table-text-muted">
                            <x-table.sortable-header 
                                field="id" 
                                :sortField="$sort['field'] ?? null" 
                                :sortDirection="$sort['direction'] ?? 'asc'"
                                :nextDirection="$sort['nextDirection'] ?? 'desc'"
                                class="justify-start"
                            >
                                Applicant
                            </x-table.sortable-header>
                        </th>
                        <th class="text-left py-3 px-4 text-sm table-text-muted">
                            <x-table.sortable-header 
                                field="email" 
                                :sortField="$sort['field'] ?? null" 
                                :sortDirection="$sort['direction'] ?? 'asc'"
                                :nextDirection="$sort['nextDirection'] ?? 'desc'"
                                class="justify-start"
                            >
                                Email
                            </x-table.sortable-header>
                        </th>
                        <th class="text-left py-3 px-4 text-sm table-text-muted">
                            <div class="flex items-center">
                                <span>Documents</span>
                            </div>
                        </th>
                        <th class="text-left py-3 px-4 text-sm table-text-muted">
                            <x-table.sortable-header 
                                field="status" 
                                :sortField="$sort['field'] ?? null" 
                                :sortDirection="$sort['direction'] ?? 'asc'"
                                :nextDirection="$sort['nextDirection'] ?? 'desc'"
                                class="justify-start"
                            >
                                Status
                            </x-table.sortable-header>
                        </th>
                        <th class="text-left py-3 px-4 text-sm table-text-muted">
                            <x-table.sortable-header 
                                field="submitted_at" 
                                :sortField="$sort['field'] ?? null" 
                                :sortDirection="$sort['direction'] ?? 'asc'"
                                :nextDirection="$sort['nextDirection'] ?? 'desc'"
                                class="justify-start"
                            >
                                Date
                            </x-table.sortable-header>
                        </th>
                        <th class="text-left py-3 px-4 text-sm table-text-muted">
                            <x-table.sortable-header 
                                field="certificate_status" 
                                :sortField="$sort['field'] ?? null" 
                                :sortDirection="$sort['direction'] ?? 'asc'"
                                :nextDirection="$sort['nextDirection'] ?? 'desc'"
                                class="justify-start"
                            >
                                Certificate
                            </x-table.sortable-header>
                        </th>
                        <th class="text-left py-3 px-4 text-sm table-text-muted">
                            <div class="flex items-center">
                                <span>Action</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentApplications as $applicant)
                    @if($applicant)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="py-4 px-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-sm font-bold text-white">{{ strtoupper(substr($applicant->name, 0, 2)) }}</span>
                                </div>
                                <div>
                                    <div class="font-medium table-text">{{ $applicant->name }}</div>
                                    <div class="table-text-muted text-sm">#{{ str_pad($applicant->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 table-text">{{ Str::limit($applicant->email, 30) }}</td>
                        <td class="py-4 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $applicant->uploads->count() }} files
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            @if($applicant->status === 'pending' || $applicant->status === 'in_verification')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400">Pending</span>
                            @elseif($applicant->status === 'verified')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400">Verified</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">Rejected</span>
                            @endif
                        </td>
                    @endif
                        <td class="py-4 px-4">
                            <div class="table-text">{{ $applicant->submitted_at->format('M j, Y') }}</div>
                            <div class="table-text-muted text-sm">{{ $applicant->submitted_at->diffForHumans() }}</div>
                        </td>
                        <td class="py-4 px-4">
                            @if($applicant->certificates->count() > 0)
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Generated
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            <a 
                                href="{{ route('admin.applicants.show', $applicant) }}" 
                                @click.stop 
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
                            >
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center table-text-muted">
                            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg font-medium">No applications found</p>
                            <p class="text-sm">Applications will appear here once submitted</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');

    // Consistent colors for both light and dark modes
    const colors = {
        primary: isDark ? '#60a5fa' : '#3b82f6',
        success: isDark ? '#10b981' : '#10b981',
        warning: isDark ? '#f59e0b' : '#f59e0b',
        danger: isDark ? '#ef4444' : '#ef4444',
        text: isDark ? '#f9fafb' : '#111827',
        background: isDark ? '#1f2937' : '#ffffff',
        grid: isDark ? 'rgba(107, 114, 128, 0.2)' : 'rgba(156, 163, 175, 0.3)',
        axis: isDark ? '#9ca3af' : '#6b7280',
        chartArea: isDark ? 'rgba(96, 165, 250, 0.08)' : 'rgba(59, 130, 246, 0.05)'
    };
    
    // Base chart options
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: {
                top: 20,
                right: 20,
                bottom: 10,
                left: 10
            }
        },
        plugins: {
            legend: { 
                display: false 
            },
            tooltip: {
                backgroundColor: isDark ? '#374151' : '#ffffff',
                titleColor: isDark ? '#ffffff' : '#111827',
                bodyColor: isDark ? '#e5e7eb' : '#4b5563',
                borderColor: isDark ? '#4b5563' : '#e5e7eb',
                borderWidth: 1,
                cornerRadius: 8,
                padding: 12,
                titleFont: {
                    size: 13,
                    weight: '600',
                    family: "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
                },
                bodyFont: {
                    size: 13,
                    family: "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
                },
                displayColors: false
            }
        },
        scales: {
            x: {
                grid: {
                    color: 'transparent',
                    drawBorder: false,
                    drawOnChartArea: false
                },
                ticks: {
                    color: colors.axis,
                    font: {
                        size: 12,
                        weight: '500',
                        family: "'Inter', system-ui, -apple-system, sans-serif"
                    },
                    padding: 8
                }
            },
            y: {
                beginAtZero: true,
                suggestedMax: 10, // Adjust based on your data range
                grid: {
                    color: colors.grid,
                    drawBorder: false,
                    drawTicks: false,
                    borderDash: [4, 4],
                    drawOnChartArea: true
                },
                ticks: {
                    stepSize: 2,
                    precision: 0,
                    color: colors.axis,
                    font: {
                        size: 11,
                        weight: '500',
                        family: "'Inter', system-ui, -apple-system, sans-serif"
                    },
                    padding: 8,
                    callback: function(value) {
                        return Number.isInteger(value) ? value : '';
                    }
                },
                border: { display: false }
            }
        }
    };

    // Modern Line Chart
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        // Create gradient for the chart area
        const gradient = monthlyCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, isDark ? 'rgba(96, 165, 250, 0.2)' : 'rgba(37, 99, 235, 0.1)');
        gradient.addColorStop(1, isDark ? 'rgba(0, 0, 0, 0)' : 'rgba(255, 255, 255, 0)');

        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: @json($monthlyData->pluck('label')),
                datasets: [{
                    label: 'Applications',
                    data: @json($monthlyData->pluck('count')),
                    borderColor: colors.primary,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.3,
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointBackgroundColor: colors.background,
                    pointBorderColor: colors.primary,
                    pointBorderWidth: 2,
                    pointHoverBorderWidth: 2,
                    pointHoverBackgroundColor: colors.primary,
                    pointHitRadius: 20,
                    pointHoverBorderColor: colors.background
                }]
            },
            options: {
                ...chartOptions,
                elements: {
                    line: {
                        borderJoinStyle: 'round'
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    ...chartOptions.plugins,
                    tooltip: {
                        ...chartOptions.plugins.tooltip,
                        callbacks: {
                            label: function(context) {
                                return ` ${context.parsed.y} applications`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Donut Chart
    const applicationStatusCtx = document.getElementById('applicationStatusChart');
    if (applicationStatusCtx) {
        new Chart(applicationStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Verified', 'Rejected'],
                datasets: [{
                    data: [
                        @json($applicationStatusStats['pending']),
                        @json($applicationStatusStats['verified']),
                        @json($applicationStatusStats['rejected'])
                    ],
                    backgroundColor: ['#fbbf24', '#10b981', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: colors.background,
                        titleColor: colors.text,
                        bodyColor: colors.muted,
                        borderColor: colors.grid,
                        borderWidth: 1,
                        cornerRadius: 8
                    }
                }
            }
        });
    }
});
</script>
@endsection
