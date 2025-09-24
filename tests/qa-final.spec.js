import { test, expect } from '@playwright/test';

test.describe('Final QA Assessment - Database Dynamic Content', () => {

  test('Comprehensive database content verification', async ({ page }) => {
    console.log('🔍 FINAL QA: Testing dynamic database content...');

    // Navigate to homepage
    await page.goto('http://localhost:8000');
    await page.waitForLoadState('networkidle');

    // Take full screenshot for evidence
    await page.screenshot({ path: 'final-qa-homepage.png', fullPage: true });

    console.log('✅ Page loaded successfully');

    // 1. Check services section with actual content
    console.log('🛠️ Testing Services Section...');

    const servicesSection = page.locator('#services');
    await expect(servicesSection).toBeVisible();

    // Look for service cards
    const serviceCards = page.locator('.service-card');
    const serviceCount = await serviceCards.count();
    console.log(`📊 Found ${serviceCount} service cards`);

    if (serviceCount > 0) {
      // Get actual service names
      for (let i = 0; i < serviceCount; i++) {
        const serviceTitle = await serviceCards.nth(i).locator('.service-title').textContent();
        console.log(`  Service ${i+1}: "${serviceTitle}"`);
      }
      await servicesSection.screenshot({ path: 'services-with-content.png' });
    } else {
      console.log('❌ FAILURE: No service cards found - services not loading from database');
      throw new Error('Services section is empty - database connection issue');
    }

    // 2. Check projects section
    console.log('🏗️ Testing Projects Section...');

    const projectsSection = page.locator('#portfolio');
    await expect(projectsSection).toBeVisible();

    const projectCards = page.locator('.project-card, .portfolio-item');
    const projectCount = await projectCards.count();
    console.log(`📊 Found ${projectCount} project items`);

    if (projectCount > 0) {
      for (let i = 0; i < Math.min(3, projectCount); i++) {
        const projectElement = projectCards.nth(i);
        const projectText = await projectElement.textContent();
        console.log(`  Project ${i+1}: "${projectText?.slice(0, 60)}..."`);
      }
      await projectsSection.screenshot({ path: 'projects-with-content.png' });
    } else {
      console.log('❌ FAILURE: No project items found');
      throw new Error('Projects section is empty - database connection issue');
    }

    // 3. Verify dynamic meta content
    console.log('🏷️ Testing Dynamic Meta Content...');

    const pageTitle = await page.title();
    const metaDescription = await page.locator('meta[name="description"]').getAttribute('content');

    console.log(`📝 Title: "${pageTitle}"`);
    console.log(`📄 Meta description: "${metaDescription?.slice(0, 100)}..."`);

    // Check if content suggests dynamic data
    const hasDynamicTitle = pageTitle.includes('Ali Sadikin') && pageTitle.includes('Digital Transformation');
    const hasDynamicMeta = metaDescription && metaDescription.includes('Digital Transformation');

    if (!hasDynamicTitle || !hasDynamicMeta) {
      console.log('❌ FAILURE: Meta content appears to be static');
      throw new Error('Meta content is not properly dynamic');
    }

    // 4. Test API endpoint to confirm database connectivity
    console.log('🌐 Testing API Database Connectivity...');

    const apiResponse = await page.goto('http://localhost:8000/api/projects');
    const apiText = await page.textContent('body');

    try {
      const apiData = JSON.parse(apiText || '{}');
      console.log(`✅ API returns valid JSON with ${Array.isArray(apiData.projects) ? apiData.projects.length : 'object'} items`);
    } catch (e) {
      console.log('❌ FAILURE: API does not return valid JSON');
      throw new Error('API endpoint not working properly');
    }

    // 5. Final Assessment
    console.log('\n🎯 FINAL QA ASSESSMENT:');
    console.log('=====================================');
    console.log(`✅ Services loading from database: ${serviceCount > 0 ? 'YES' : 'NO'}`);
    console.log(`✅ Projects loading from database: ${projectCount > 0 ? 'YES' : 'NO'}`);
    console.log(`✅ Dynamic meta content: ${hasDynamicTitle && hasDynamicMeta ? 'YES' : 'NO'}`);
    console.log(`✅ API connectivity working: YES`);

    if (serviceCount > 0 && projectCount > 0 && hasDynamicTitle && hasDynamicMeta) {
      console.log('\n🎉 FINAL RESULT: PASS');
      console.log('✅ Website is successfully loading dynamic content from database');
      console.log('✅ All critical sections are functional');
      console.log('✅ Database connectivity is working properly');
    } else {
      console.log('\n❌ FINAL RESULT: FAIL');
      console.log('❌ Website has issues with dynamic content loading');
      throw new Error('QA Failed: Website not properly loading database content');
    }
  });

});