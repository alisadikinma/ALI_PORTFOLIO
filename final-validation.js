import { chromium } from 'playwright';

async function finalValidation() {
    console.log('🎯 FINAL VALIDATION: Testing all 4 critical bugs...\n');

    const browser = await chromium.launch({ headless: false });
    const page = await browser.newPage();

    const results = {
        bug1_lightMode: false,
        bug2_ctaButtons: false,
        bug3_awardsMenu: false,
        bug4_servicesMenu: false,
        allPassed: false
    };

    try {
        // Navigate to portfolio
        console.log('📍 Loading portfolio page...');
        await page.goto('http://localhost/ALI_PORTFOLIO/public/', {
            waitUntil: 'networkidle',
            timeout: 30000
        });

        // Wait for JavaScript to initialize
        await page.waitForTimeout(4000);
        console.log('✅ Page loaded and JavaScript initialized\n');

        // ============================================
        // BUG 1: Light Mode Theme Toggle
        // ============================================
        console.log('🔍 Testing Bug 1: Light Mode Theme Toggle...');

        try {
            // Wait for theme toggle to appear
            await page.waitForSelector('#theme-toggle', { timeout: 5000 });
            console.log('✅ Theme toggle found');

            // Take screenshot of initial state
            await page.screenshot({ path: 'validation-1-initial.png', fullPage: true });

            // Get initial background color
            const initialBg = await page.evaluate(() => {
                return getComputedStyle(document.body).backgroundColor;
            });
            console.log(`📊 Initial background: ${initialBg}`);

            // Click theme toggle multiple times to test all modes
            let lightBg = initialBg;
            let darkBg = initialBg;
            let foundDarkMode = false;

            for (let i = 0; i < 4; i++) {
                await page.click('#theme-toggle');
                await page.waitForTimeout(1000);

                const currentBg = await page.evaluate(() => {
                    return getComputedStyle(document.body).backgroundColor;
                });

                const dataTheme = await page.getAttribute('html', 'data-theme');
                console.log(`📊 Toggle ${i+1}: data-theme="${dataTheme || 'none'}", bg="${currentBg}"`);

                if (dataTheme === 'dark') {
                    darkBg = currentBg;
                    foundDarkMode = true;
                } else {
                    lightBg = currentBg;
                }
            }

            // Take screenshot after testing
            await page.screenshot({ path: 'validation-1-after-toggle.png', fullPage: true });

            console.log(`📊 Light mode background: ${lightBg}`);
            console.log(`📊 Dark mode background: ${darkBg}`);

            // Check if we found both modes and they're different
            if (foundDarkMode && lightBg !== darkBg) {
                results.bug1_lightMode = true;
                console.log('✅ Bug 1 FIXED: Theme toggle switches between light and dark modes');
            } else {
                console.log('❌ Bug 1 FAILED: Could not achieve both light and dark modes with different backgrounds');
            }

        } catch (error) {
            console.log('❌ Bug 1 FAILED: Could not test theme toggle -', error.message);
        }

        console.log('');

        // ============================================
        // BUG 2: CTA Button Visibility in Dark Mode
        // ============================================
        console.log('🔍 Testing Bug 2: CTA Button Visibility...');

        try {
            // Ensure we're in dark mode
            const dataTheme = await page.getAttribute('html', 'data-theme');
            if (!dataTheme || dataTheme !== 'dark') {
                await page.click('#theme-toggle');
                await page.waitForTimeout(1000);
            }

            // Find CTA buttons
            const ctaButtons = await page.locator('a:has-text("Say Hello"), a:has-text("Send message"), .btn-primary').all();
            console.log(`📊 Found ${ctaButtons.length} CTA buttons`);

            let visibleButtons = 0;
            for (let i = 0; i < ctaButtons.length; i++) {
                const isVisible = await ctaButtons[i].isVisible();
                const color = await ctaButtons[i].evaluate(el => getComputedStyle(el).color);
                const bgColor = await ctaButtons[i].evaluate(el => getComputedStyle(el).backgroundColor);

                console.log(`📊 Button ${i+1}: visible=${isVisible}, color=${color}, bg=${bgColor}`);

                if (isVisible && (color !== 'rgb(0, 0, 0)' || bgColor !== 'rgba(0, 0, 0, 0)')) {
                    visibleButtons++;
                }
            }

            if (visibleButtons > 0) {
                results.bug2_ctaButtons = true;
                console.log(`✅ Bug 2 FIXED: ${visibleButtons} CTA buttons are visible in dark mode`);
            } else {
                console.log('❌ Bug 2 FAILED: No CTA buttons are visible in dark mode');
            }

        } catch (error) {
            console.log('❌ Bug 2 FAILED: Could not test CTA buttons -', error.message);
        }

        console.log('');

        // ============================================
        // BUG 3 & 4: Menu Highlighting
        // ============================================
        console.log('🔍 Testing Bug 3 & 4: Menu Highlighting...');

        try {
            // Function to get active menu item
            const getActiveMenu = async () => {
                return await page.evaluate(() => {
                    const activeLink = document.querySelector('#nav-menu a.text-yellow-400, #nav-menu a.font-semibold');
                    return activeLink ? activeLink.textContent.trim() : 'none';
                });
            };

            // Initial state - should be Home
            let activeMenu = await getActiveMenu();
            console.log(`📊 Initial active menu: ${activeMenu}`);

            // Scroll to Awards section
            console.log('📍 Scrolling to Awards section...');
            await page.evaluate(() => {
                const awardsSection = document.getElementById('awards');
                if (awardsSection) {
                    awardsSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });

            await page.waitForTimeout(2000); // Wait for scroll and intersection observer

            activeMenu = await getActiveMenu();
            console.log(`📊 Active menu after scrolling to Awards: ${activeMenu}`);

            if (activeMenu.toLowerCase().includes('award')) {
                results.bug3_awardsMenu = true;
                console.log('✅ Bug 3 FIXED: Awards menu highlights when scrolling to Awards section');
            } else {
                console.log('❌ Bug 3 FAILED: Awards menu does not highlight - instead: ' + activeMenu);
            }

            // Scroll to Services section
            console.log('📍 Scrolling to Services section...');
            await page.evaluate(() => {
                const servicesSection = document.getElementById('services');
                if (servicesSection) {
                    servicesSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });

            await page.waitForTimeout(2000); // Wait for scroll and intersection observer

            activeMenu = await getActiveMenu();
            console.log(`📊 Active menu after scrolling to Services: ${activeMenu}`);

            if (activeMenu.toLowerCase().includes('service')) {
                results.bug4_servicesMenu = true;
                console.log('✅ Bug 4 FIXED: Services menu highlights when scrolling to Services section');
            } else {
                console.log('❌ Bug 4 FAILED: Services menu does not highlight - instead: ' + activeMenu);
            }

        } catch (error) {
            console.log('❌ Bug 3&4 FAILED: Could not test menu highlighting -', error.message);
        }

        // Take final screenshot
        await page.screenshot({ path: 'validation-final.png', fullPage: true });

        // ============================================
        // FINAL RESULTS
        // ============================================
        console.log('\n🎯 FINAL VALIDATION RESULTS:');
        console.log('==========================================');
        console.log(`Bug 1 (Light Mode): ${results.bug1_lightMode ? '✅ PASS' : '❌ FAIL'}`);
        console.log(`Bug 2 (CTA Buttons): ${results.bug2_ctaButtons ? '✅ PASS' : '❌ FAIL'}`);
        console.log(`Bug 3 (Awards Menu): ${results.bug3_awardsMenu ? '✅ PASS' : '❌ FAIL'}`);
        console.log(`Bug 4 (Services Menu): ${results.bug4_servicesMenu ? '✅ PASS' : '❌ FAIL'}`);
        console.log('==========================================');

        const passedCount = Object.values(results).filter(Boolean).length - 1; // -1 for allPassed field
        results.allPassed = passedCount === 4;

        if (results.allPassed) {
            console.log('🎉 SUCCESS: ALL 4 BUGS HAVE BEEN FIXED! 🎉');
        } else {
            console.log(`⚠️  ${passedCount}/4 bugs fixed. Need to address remaining issues.`);
        }

    } catch (error) {
        console.error('❌ Validation failed:', error.message);
    } finally {
        await browser.close();
    }

    return results;
}

finalValidation().catch(console.error);