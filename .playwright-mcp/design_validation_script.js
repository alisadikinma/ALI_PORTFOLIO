// Design Validation Playwright Script
// This will be executed to capture screenshots for design validation

import { chromium } from 'playwright';

async function validateDesign() {
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext();

    // Desktop validation - 1440px (Professional presentation viewport)
    const page = await context.newPage();
    await page.setViewportSize({ width: 1440, height: 900 });

    try {
        // Navigate to homepage
        await page.goto('http://localhost/ALI_PORTFOLIO/public/', {
            waitUntil: 'domcontentloaded',
            timeout: 30000
        });

        // Wait for content to load
        await page.waitForTimeout(3000);

        // Take full page screenshot
        await page.screenshot({
            path: 'homepage-desktop-1440px.png',
            fullPage: true,
            quality: 95
        });

        console.log('✅ Desktop homepage screenshot captured');

    } catch (error) {
        console.error('❌ Error capturing desktop screenshot:', error);
    }

    await browser.close();
}

validateDesign();