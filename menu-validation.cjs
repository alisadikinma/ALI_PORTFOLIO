const { chromium } = require('playwright');

async function validateMenuHighlighting() {
    console.log('Testing menu highlighting with correct CSS classes...');

    const browser = await chromium.launch({ headless: false, slowMo: 1000 });
    const context = await browser.newContext({ viewport: { width: 1920, height: 1080 } });
    const page = await context.newPage();

    try {
        await page.goto('http://localhost/ALI_PORTFOLIO/public/', { waitUntil: 'networkidle' });
        await page.waitForTimeout(3000);

        console.log('=== Testing Awards Section Highlighting ===');

        // Scroll to awards section
        const awardsSection = await page.locator('#awards').first();
        if (await awardsSection.count() > 0) {
            await awardsSection.evaluate(el => el.scrollIntoView({ behavior: 'smooth' }));
            await page.waitForTimeout(3000); // Wait for highlighting

            // Check menu highlighting with correct CSS classes
            const menuLinks = await page.locator('#nav-menu a').all();
            let highlightedMenus = [];

            for (let i = 0; i < menuLinks.length; i++) {
                const link = menuLinks[i];
                const linkText = await link.textContent();
                const hasYellowText = await link.evaluate(el =>
                    el.classList.contains('text-yellow-400')
                );
                const hasFontSemibold = await link.evaluate(el =>
                    el.classList.contains('font-semibold')
                );

                if (hasYellowText || hasFontSemibold) {
                    highlightedMenus.push({
                        text: linkText?.trim(),
                        hasYellow: hasYellowText,
                        hasSemibold: hasFontSemibold
                    });
                }
            }

            console.log('Awards section - Highlighted menus:', highlightedMenus);
        }

        console.log('=== Testing Services Section Highlighting ===');

        // Scroll to services section
        const servicesSection = await page.locator('#services').first();
        if (await servicesSection.count() > 0) {
            await servicesSection.evaluate(el => el.scrollIntoView({ behavior: 'smooth' }));
            await page.waitForTimeout(3000); // Wait for highlighting

            // Check menu highlighting
            const menuLinks = await page.locator('#nav-menu a').all();
            let highlightedMenus = [];

            for (let i = 0; i < menuLinks.length; i++) {
                const link = menuLinks[i];
                const linkText = await link.textContent();
                const hasYellowText = await link.evaluate(el =>
                    el.classList.contains('text-yellow-400')
                );
                const hasFontSemibold = await link.evaluate(el =>
                    el.classList.contains('font-semibold')
                );

                if (hasYellowText || hasFontSemibold) {
                    highlightedMenus.push({
                        text: linkText?.trim(),
                        hasYellow: hasYellowText,
                        hasSemibold: hasFontSemibold
                    });
                }
            }

            console.log('Services section - Highlighted menus:', highlightedMenus);
        }

    } catch (error) {
        console.error('Error during validation:', error);
    }

    await browser.close();
}

validateMenuHighlighting().catch(console.error);