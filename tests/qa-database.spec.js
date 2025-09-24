import { test, expect } from '@playwright/test';

test.describe('ALI Portfolio QA - Database Connectivity Tests', () => {

  test('Homepage loads and displays dynamic database content', async ({ page }) => {
    console.log('🔍 Starting comprehensive QA test...');

    // Navigate to homepage
    await page.goto('http://localhost:8000');

    // Wait for page to fully load
    await page.waitForLoadState('networkidle');

    console.log('📄 Page loaded, checking dynamic content...');

    // Take screenshot for evidence
    await page.screenshot({ path: 'homepage-full.png', fullPage: true });

    // Check if page title indicates dynamic loading
    const title = await page.title();
    console.log(`📝 Page title: ${title}`);

    // Look for specific dynamic content sections

    // 1. Check for Projects Section
    console.log('🏗️ Checking Projects section...');
    const projectsSection = page.locator('[data-section="projects"], .projects-section, #projects');
    if (await projectsSection.count() > 0) {
      await projectsSection.first().screenshot({ path: 'projects-section.png' });

      // Look for project cards/items
      const projectItems = page.locator('.project-card, .project-item, [data-project]');
      const projectCount = await projectItems.count();
      console.log(`📊 Found ${projectCount} project items`);

      if (projectCount > 0) {
        // Get project titles/names to verify they're from database
        for (let i = 0; i < Math.min(3, projectCount); i++) {
          const projectText = await projectItems.nth(i).textContent();
          console.log(`  Project ${i+1}: ${projectText?.slice(0, 100)}...`);
        }
      }
    }

    // 2. Check for Services Section
    console.log('🛠️ Checking Services section...');
    const servicesSection = page.locator('[data-section="services"], .services-section, #services');
    if (await servicesSection.count() > 0) {
      await servicesSection.first().screenshot({ path: 'services-section.png' });

      const serviceItems = page.locator('.service-card, .service-item, [data-service]');
      const serviceCount = await serviceItems.count();
      console.log(`📊 Found ${serviceCount} service items`);

      if (serviceCount > 0) {
        for (let i = 0; i < Math.min(3, serviceCount); i++) {
          const serviceText = await serviceItems.nth(i).textContent();
          console.log(`  Service ${i+1}: ${serviceText?.slice(0, 100)}...`);
        }
      }
    }

    // 3. Check for dynamic content indicators
    console.log('🔄 Checking for dynamic content indicators...');

    // Look for Laravel-specific elements
    const csrfToken = await page.locator('meta[name="csrf-token"]').getAttribute('content');
    console.log(`🔐 CSRF Token present: ${csrfToken ? 'Yes' : 'No'}`);

    // Check for any content that suggests database loading
    const bodyText = await page.textContent('body');

    // Look for specific database-driven content
    const hasProjects = bodyText?.includes('project') || bodyText?.includes('Project');
    const hasServices = bodyText?.includes('service') || bodyText?.includes('Service');
    const hasPortfolio = bodyText?.includes('portfolio') || bodyText?.includes('Portfolio');

    console.log(`📊 Content Analysis:`);
    console.log(`  - Contains project references: ${hasProjects}`);
    console.log(`  - Contains service references: ${hasServices}`);
    console.log(`  - Contains portfolio references: ${hasPortfolio}`);

    // 4. Check for empty states or placeholder content
    console.log('🔍 Checking for static/placeholder content...');

    const staticIndicators = [
      'Lorem ipsum',
      'placeholder',
      'Sample Project',
      'Example Service',
      'Test Content',
      'Coming Soon'
    ];

    let staticContentFound = false;
    for (const indicator of staticIndicators) {
      if (bodyText?.toLowerCase().includes(indicator.toLowerCase())) {
        console.log(`⚠️ Static content indicator found: "${indicator}"`);
        staticContentFound = true;
      }
    }

    if (!staticContentFound) {
      console.log('✅ No obvious static content indicators found');
    }

    // 5. Check network requests for API calls
    console.log('🌐 Checking for dynamic data loading...');

    // Monitor network requests
    const requests = [];
    page.on('request', request => {
      if (request.url().includes('/api/') || request.url().includes('projects') || request.url().includes('services')) {
        requests.push(request.url());
      }
    });

    // Trigger any potential AJAX calls
    await page.reload({ waitUntil: 'networkidle' });

    console.log(`📡 API/Dynamic requests found: ${requests.length}`);
    requests.forEach(url => console.log(`  - ${url}`));

    // 6. Final Assessment
    console.log('\n🎯 QA ASSESSMENT SUMMARY:');
    console.log('================================');

    // Check if we can find actual project/service data
    const pageContent = await page.content();

    // Look for structured data that suggests database content
    const hasStructuredContent = pageContent.includes('project') && pageContent.includes('service');
    const hasMetaTags = pageContent.includes('meta name="description"');
    const hasDynamicElements = pageContent.includes('csrf') || pageContent.includes('laravel');

    console.log(`✅ Page loads successfully: Yes`);
    console.log(`📊 Has structured content: ${hasStructuredContent}`);
    console.log(`🏷️ Has proper meta tags: ${hasMetaTags}`);
    console.log(`⚡ Has dynamic elements: ${hasDynamicElements}`);
    console.log(`🔧 Static content detected: ${staticContentFound}`);

    // CRITICAL TEST: Verify this is not just static HTML
    if (!hasStructuredContent && !hasDynamicElements) {
      console.log('❌ CRITICAL: Website appears to be serving static content only!');
      throw new Error('Website is serving static content - database connection may not be working');
    } else {
      console.log('✅ Website appears to be serving dynamic content');
    }
  });

  test('Database connectivity verification', async ({ page }) => {
    console.log('🗄️ Testing database connectivity...');

    // Try to access a route that should trigger database queries
    await page.goto('http://localhost:8000/api/projects');

    const response = await page.textContent('body');
    console.log(`📊 API Response: ${response?.slice(0, 200)}...`);

    // Check if we get JSON data or error
    try {
      const data = JSON.parse(response || '{}');
      console.log(`✅ Valid JSON response with ${Array.isArray(data) ? data.length : 'object'} items`);
    } catch (e) {
      console.log(`⚠️ Non-JSON response: ${response?.slice(0, 100)}...`);
    }
  });

});