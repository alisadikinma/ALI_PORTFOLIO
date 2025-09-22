// Design Validation Script for Ali's Digital Transformation Portfolio
// Comprehensive analysis across critical viewports

const { chromium } = require('playwright');

async function validateDesignSystem() {
    const browser = await chromium.launch();
    const context = await browser.newContext();

    // Critical viewport configurations for professional consulting
    const viewports = [
        { name: 'desktop', width: 1440, height: 900 },
        { name: 'tablet', width: 768, height: 1024 },
        { name: 'mobile', width: 375, height: 812 }
    ];

    const pages = [
        { name: 'homepage', url: 'http://localhost/ALI_PORTFOLIO/public/' },
        { name: 'admin-login', url: 'http://localhost/ALI_PORTFOLIO/public/login' }
    ];

    for (const viewport of viewports) {
        const page = await context.newPage();
        await page.setViewportSize({ width: viewport.width, height: viewport.height });

        for (const pageConfig of pages) {
            try {
                await page.goto(pageConfig.url, { waitUntil: 'networkidle' });
                await page.waitForTimeout(2000);

                // Take full page screenshot
                await page.screenshot({
                    path: `C:\\xampp\\htdocs\\ALI_PORTFOLIO\\.playwright-mcp\\design-validation-${pageConfig.name}-${viewport.name}.png`,
                    fullPage: true
                });

                console.log(`✅ Captured ${pageConfig.name} at ${viewport.name} (${viewport.width}x${viewport.height})`);

                // Check for critical design elements
                const designElements = await page.evaluate(() => {
                    return {
                        heroSection: !!document.querySelector('#home'),
                        navigation: !!document.querySelector('#nav-menu'),
                        responsiveGrid: !!document.querySelector('.grid'),
                        modernCard: !!document.querySelector('.card-modern'),
                        glassEffect: !!document.querySelector('.glass-effect'),
                        gradientText: !!document.querySelector('.text-gradient-neon'),
                        animatedElements: !!document.querySelector('[class*="animate-"]'),
                        accessibilityAttributes: !!document.querySelector('[aria-label]'),
                        focusElements: !!document.querySelector('[tabindex]'),
                        semanticHTML: !!document.querySelector('main, section, nav, header, footer')
                    };
                });

                console.log(`Design Elements Check for ${pageConfig.name} (${viewport.name}):`, designElements);

            } catch (error) {
                console.error(`❌ Error validating ${pageConfig.name} at ${viewport.name}:`, error.message);
            }
        }

        await page.close();
    }

    await browser.close();
    console.log('\n🎨 Design validation complete! Screenshots saved to .playwright-mcp/');
}

validateDesignSystem();