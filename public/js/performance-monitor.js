/**
 * Core Web Vitals Performance Monitoring
 * Tracks LCP, FID, CLS and sends data to server for analysis
 */

class PerformanceMonitor {
    constructor() {
        this.metrics = {};
        this.isReportingSent = false;
        this.init();
    }

    init() {
        // Monitor Core Web Vitals when available
        if ('web-vitals' in window) {
            this.initWebVitals();
        } else {
            // Fallback manual monitoring
            this.initManualMonitoring();
        }

        // Send report when page is about to unload
        window.addEventListener('beforeunload', () => this.sendReport());

        // Send report after 30 seconds for long sessions
        setTimeout(() => this.sendReport(), 30000);
    }

    initWebVitals() {
        // This would use the web-vitals library if included
        console.log('Web Vitals library not found, using manual monitoring');
        this.initManualMonitoring();
    }

    initManualMonitoring() {
        // Monitor Largest Contentful Paint (LCP)
        this.monitorLCP();

        // Monitor First Input Delay (FID)
        this.monitorFID();

        // Monitor Cumulative Layout Shift (CLS)
        this.monitorCLS();

        // Monitor additional performance metrics
        this.monitorNavigation();

        // Monitor resource loading
        this.monitorResources();
    }

    monitorLCP() {
        // Use PerformanceObserver to track LCP
        try {
            const observer = new PerformanceObserver((entryList) => {
                const entries = entryList.getEntries();
                const lastEntry = entries[entries.length - 1];

                this.metrics.lcp = {
                    value: lastEntry.startTime,
                    rating: this.rateLCP(lastEntry.startTime),
                    timestamp: Date.now()
                };
            });

            observer.observe({ entryTypes: ['largest-contentful-paint'] });
        } catch (e) {
            // Fallback: Use load event timing
            window.addEventListener('load', () => {
                const loadTime = performance.now();
                this.metrics.lcp = {
                    value: loadTime,
                    rating: this.rateLCP(loadTime),
                    timestamp: Date.now(),
                    fallback: true
                };
            });
        }
    }

    monitorFID() {
        let firstInputDelay = null;

        const firstInputHandler = (event) => {
            if (firstInputDelay === null) {
                firstInputDelay = performance.now() - event.timeStamp;

                this.metrics.fid = {
                    value: firstInputDelay,
                    rating: this.rateFID(firstInputDelay),
                    timestamp: Date.now()
                };

                // Remove listeners after first input
                ['mousedown', 'keydown', 'touchstart', 'pointerdown'].forEach(type => {
                    document.removeEventListener(type, firstInputHandler, true);
                });
            }
        };

        // Listen for first input
        ['mousedown', 'keydown', 'touchstart', 'pointerdown'].forEach(type => {
            document.addEventListener(type, firstInputHandler, true);
        });
    }

    monitorCLS() {
        let clsValue = 0;
        let sessionValue = 0;
        let sessionEntries = [];

        try {
            const observer = new PerformanceObserver((entryList) => {
                for (const entry of entryList.getEntries()) {
                    if (!entry.hadRecentInput) {
                        const firstSessionEntry = sessionEntries[0];
                        const lastSessionEntry = sessionEntries[sessionEntries.length - 1];

                        if (sessionValue &&
                            entry.startTime - lastSessionEntry.startTime < 1000 &&
                            entry.startTime - firstSessionEntry.startTime < 5000) {
                            sessionValue += entry.value;
                            sessionEntries.push(entry);
                        } else {
                            sessionValue = entry.value;
                            sessionEntries = [entry];
                        }

                        if (sessionValue > clsValue) {
                            clsValue = sessionValue;

                            this.metrics.cls = {
                                value: clsValue,
                                rating: this.rateCLS(clsValue),
                                timestamp: Date.now()
                            };
                        }
                    }
                }
            });

            observer.observe({ entryTypes: ['layout-shift'] });
        } catch (e) {
            console.warn('Layout shift monitoring not supported');
        }
    }

    monitorNavigation() {
        window.addEventListener('load', () => {
            const navigation = performance.getEntriesByType('navigation')[0];

            if (navigation) {
                this.metrics.navigation = {
                    dns: navigation.domainLookupEnd - navigation.domainLookupStart,
                    tcp: navigation.connectEnd - navigation.connectStart,
                    request: navigation.responseStart - navigation.requestStart,
                    response: navigation.responseEnd - navigation.responseStart,
                    dom: navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart,
                    load: navigation.loadEventEnd - navigation.loadEventStart,
                    total: navigation.loadEventEnd - navigation.navigationStart,
                    timestamp: Date.now()
                };
            }
        });
    }

    monitorResources() {
        window.addEventListener('load', () => {
            const resources = performance.getEntriesByType('resource');

            const resourceMetrics = {
                totalResources: resources.length,
                totalSize: 0,
                totalDuration: 0,
                slowResources: [],
                timestamp: Date.now()
            };

            resources.forEach(resource => {
                const duration = resource.responseEnd - resource.startTime;
                resourceMetrics.totalDuration += duration;

                if (resource.transferSize) {
                    resourceMetrics.totalSize += resource.transferSize;
                }

                // Flag slow resources (>1000ms)
                if (duration > 1000) {
                    resourceMetrics.slowResources.push({
                        name: resource.name,
                        duration: duration,
                        size: resource.transferSize || 0
                    });
                }
            });

            this.metrics.resources = resourceMetrics;
        });
    }

    // Rating functions based on Google's thresholds
    rateLCP(value) {
        if (value <= 2500) return 'good';
        if (value <= 4000) return 'needs-improvement';
        return 'poor';
    }

    rateFID(value) {
        if (value <= 100) return 'good';
        if (value <= 300) return 'needs-improvement';
        return 'poor';
    }

    rateCLS(value) {
        if (value <= 0.1) return 'good';
        if (value <= 0.25) return 'needs-improvement';
        return 'poor';
    }

    async sendReport() {
        if (this.isReportingSent || Object.keys(this.metrics).length === 0) {
            return;
        }

        this.isReportingSent = true;

        const report = {
            metrics: this.metrics,
            page: {
                url: window.location.href,
                pathname: window.location.pathname,
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                timestamp: Date.now()
            },
            viewport: {
                width: window.innerWidth,
                height: window.innerHeight,
                devicePixelRatio: window.devicePixelRatio || 1
            },
            connection: this.getConnectionInfo()
        };

        try {
            // Use sendBeacon for reliable delivery
            if (navigator.sendBeacon) {
                navigator.sendBeacon('/api/performance-metrics', JSON.stringify(report));
            } else {
                // Fallback to fetch
                fetch('/api/performance-metrics', {
                    method: 'POST',
                    body: JSON.stringify(report),
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).catch(() => {
                    // Ignore network errors for performance reporting
                });
            }
        } catch (e) {
            console.warn('Failed to send performance metrics:', e);
        }
    }

    getConnectionInfo() {
        if ('connection' in navigator) {
            const conn = navigator.connection;
            return {
                effectiveType: conn.effectiveType,
                downlink: conn.downlink,
                rtt: conn.rtt,
                saveData: conn.saveData
            };
        }
        return null;
    }

    // Public API for manual reporting
    reportCustomMetric(name, value, rating = null) {
        this.metrics[name] = {
            value: value,
            rating: rating,
            timestamp: Date.now(),
            custom: true
        };
    }

    // Get current metrics (for debugging)
    getMetrics() {
        return this.metrics;
    }
}

// Initialize performance monitoring
document.addEventListener('DOMContentLoaded', () => {
    window.performanceMonitor = new PerformanceMonitor();

    // Expose to global scope for debugging
    if (window.location.hostname === 'localhost' || window.location.hostname.includes('local')) {
        console.log('Performance monitoring initialized (debug mode)');
        console.log('Access metrics via: window.performanceMonitor.getMetrics()');
    }
});

// Report long tasks
if ('PerformanceObserver' in window) {
    try {
        const longTaskObserver = new PerformanceObserver((entryList) => {
            for (const entry of entryList.getEntries()) {
                if (window.performanceMonitor) {
                    window.performanceMonitor.reportCustomMetric('longTask', entry.duration,
                        entry.duration > 50 ? 'poor' : 'good');
                }
            }
        });

        longTaskObserver.observe({ entryTypes: ['longtask'] });
    } catch (e) {
        console.warn('Long task monitoring not supported');
    }
}