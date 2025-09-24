import { test, expect } from '@playwright/test';

test.describe('Complete QA - All Homepage Sections', () => {

  test('Validate ALL homepage sections for dynamic database content', async ({ page }) => {
    console.log('🔍 COMPREHENSIVE QA: Testing ALL homepage sections...');

    await page.goto('http://localhost:8000');
    await page.waitForLoadState('networkidle');

    const results = {
      hero: false,
      about: false,
      services: false,
      portfolio: false,
      awards: false,
      gallery: false,
      testimonials: false,
      contact: false
    };

    // 1. HERO SECTION
    console.log('\n🦸 Testing HERO Section...');
    const heroSection = page.locator('section').first();
    if (await heroSection.isVisible()) {
      const heroText = await heroSection.textContent();
      const hasPersonalInfo = heroText?.includes('Ali Sadikin') || heroText?.includes('Digital Transformation');
      const hasStats = heroText?.includes('project') || heroText?.includes('experience') || heroText?.includes('years');

      console.log(`  - Personal info present: ${hasPersonalInfo}`);
      console.log(`  - Statistics present: ${hasStats}`);

      if (hasPersonalInfo && hasStats) {
        results.hero = true;
        console.log('  ✅ Hero section: PASS');
      } else {
        console.log('  ❌ Hero section: FAIL');
      }
      await heroSection.screenshot({ path: 'hero-section.png' });
    }

    // 2. ABOUT SECTION
    console.log('\n👤 Testing ABOUT Section...');
    const aboutSection = page.locator('#about, .about-section, section:has-text("About")');
    if (await aboutSection.count() > 0) {
      const aboutText = await aboutSection.first().textContent();
      const hasAboutContent = aboutText && aboutText.length > 100;
      const hasPersonalDetails = aboutText?.toLowerCase().includes('digital transformation') ||
                                aboutText?.toLowerCase().includes('consultant') ||
                                aboutText?.toLowerCase().includes('manufacturing');

      console.log(`  - About content length: ${aboutText?.length || 0} characters`);
      console.log(`  - Professional details present: ${hasPersonalDetails}`);

      if (hasAboutContent && hasPersonalDetails) {
        results.about = true;
        console.log('  ✅ About section: PASS');
      } else {
        console.log('  ❌ About section: FAIL');
      }
      await aboutSection.first().screenshot({ path: 'about-section.png' });
    } else {
      console.log('  ⚠️ About section: NOT FOUND');
    }

    // 3. SERVICES SECTION
    console.log('\n🛠️ Testing SERVICES Section...');
    const servicesSection = page.locator('#services');
    if (await servicesSection.isVisible()) {
      const serviceCards = page.locator('.service-card');
      const serviceCount = await serviceCards.count();

      if (serviceCount >= 3) {
        // Get service names
        const serviceNames = [];
        for (let i = 0; i < serviceCount; i++) {
          const serviceName = await serviceCards.nth(i).locator('.service-title').textContent();
          serviceNames.push(serviceName?.trim());
        }

        console.log(`  - Services found: ${serviceCount}`);
        console.log(`  - Service names: ${serviceNames.join(', ')}`);

        results.services = true;
        console.log('  ✅ Services section: PASS');
      } else {
        console.log(`  ❌ Services section: FAIL (only ${serviceCount} services found)`);
      }
      await servicesSection.screenshot({ path: 'services-section.png' });
    }

    // 4. PORTFOLIO/PROJECTS SECTION
    console.log('\n🏗️ Testing PORTFOLIO Section...');
    const portfolioSection = page.locator('#portfolio, .portfolio-section');
    if (await portfolioSection.count() > 0) {
      const projectCards = page.locator('.project-card, .portfolio-item, [data-project]');
      const projectCount = await projectCards.count();

      if (projectCount >= 6) {
        // Get project titles
        const projectTitles = [];
        for (let i = 0; i < Math.min(3, projectCount); i++) {
          const projectText = await projectCards.nth(i).textContent();
          projectTitles.push(projectText?.slice(0, 50) + '...');
        }

        console.log(`  - Projects found: ${projectCount}`);
        console.log(`  - Sample projects: ${projectTitles.join(' | ')}`);

        results.portfolio = true;
        console.log('  ✅ Portfolio section: PASS');
      } else {
        console.log(`  ❌ Portfolio section: FAIL (only ${projectCount} projects found)`);
      }
      await portfolioSection.first().screenshot({ path: 'portfolio-section.png' });
    } else {
      console.log('  ⚠️ Portfolio section: NOT FOUND');
    }

    // 5. AWARDS SECTION
    console.log('\n🏆 Testing AWARDS Section...');
    const awardsSection = page.locator('#awards, .awards-section, section:has-text("Award")');
    if (await awardsSection.count() > 0) {
      const awardItems = page.locator('.award-item, .award-card, [data-award]');
      const awardCount = await awardItems.count();

      console.log(`  - Awards found: ${awardCount}`);

      if (awardCount > 0) {
        results.awards = true;
        console.log('  ✅ Awards section: PASS');
      } else {
        console.log('  ❌ Awards section: FAIL (no awards found)');
      }
      await awardsSection.first().screenshot({ path: 'awards-section.png' });
    } else {
      console.log('  ⚠️ Awards section: NOT FOUND');
    }

    // 6. GALLERY SECTION
    console.log('\n🖼️ Testing GALLERY Section...');
    const gallerySection = page.locator('#gallery, .gallery-section, section:has-text("Gallery")');
    if (await gallerySection.count() > 0) {
      const galleryItems = page.locator('.gallery-item, .gallery-image, [data-gallery]');
      const galleryCount = await galleryItems.count();

      console.log(`  - Gallery items found: ${galleryCount}`);

      if (galleryCount > 0) {
        results.gallery = true;
        console.log('  ✅ Gallery section: PASS');
      } else {
        console.log('  ❌ Gallery section: FAIL (no gallery items found)');
      }
      await gallerySection.first().screenshot({ path: 'gallery-section.png' });
    } else {
      console.log('  ⚠️ Gallery section: NOT FOUND');
    }

    // 7. TESTIMONIALS SECTION
    console.log('\n💬 Testing TESTIMONIALS Section...');
    const testimonialsSection = page.locator('#testimonials, .testimonials-section');
    if (await testimonialsSection.count() > 0) {
      const testimonialItems = page.locator('.testimonial-item, .testimonial-card, [data-testimonial]');
      const testimonialCount = await testimonialItems.count();

      console.log(`  - Testimonials found: ${testimonialCount}`);

      if (testimonialCount > 0) {
        results.testimonials = true;
        console.log('  ✅ Testimonials section: PASS');
      } else {
        console.log('  ❌ Testimonials section: FAIL (no testimonials found)');
      }
      await testimonialsSection.first().screenshot({ path: 'testimonials-section.png' });
    } else {
      console.log('  ⚠️ Testimonials section: NOT FOUND');
    }

    // 8. CONTACT SECTION
    console.log('\n📞 Testing CONTACT Section...');
    const contactSection = page.locator('#contact, .contact-section');
    if (await contactSection.count() > 0) {
      const contactForm = page.locator('form, .contact-form');
      const contactInfo = page.locator('.contact-info, .contact-details');

      const hasForm = await contactForm.count() > 0;
      const hasInfo = await contactInfo.count() > 0;

      console.log(`  - Contact form present: ${hasForm}`);
      console.log(`  - Contact info present: ${hasInfo}`);

      if (hasForm || hasInfo) {
        results.contact = true;
        console.log('  ✅ Contact section: PASS');
      } else {
        console.log('  ❌ Contact section: FAIL (no form or info found)');
      }
      await contactSection.first().screenshot({ path: 'contact-section.png' });
    } else {
      console.log('  ⚠️ Contact section: NOT FOUND');
    }

    // FINAL COMPREHENSIVE ASSESSMENT
    console.log('\n🎯 COMPREHENSIVE QA RESULTS:');
    console.log('==========================================');

    const passedSections = Object.entries(results).filter(([_, passed]) => passed);
    const totalSections = Object.keys(results).length;
    const passRate = (passedSections.length / totalSections * 100).toFixed(1);

    console.log(`✅ Sections PASSED: ${passedSections.length}/${totalSections} (${passRate}%)`);
    console.log('');

    Object.entries(results).forEach(([section, passed]) => {
      const status = passed ? '✅ PASS' : '❌ FAIL';
      console.log(`  ${section.toUpperCase()}: ${status}`);
    });

    console.log('');

    if (passRate >= 75) {
      console.log('🎉 OVERALL RESULT: PASS');
      console.log('✅ Website has sufficient dynamic content and functionality');
    } else {
      console.log('❌ OVERALL RESULT: FAIL');
      console.log('❌ Website lacks sufficient dynamic content');
      throw new Error(`QA Failed: Only ${passRate}% of sections passed validation`);
    }

    // Take final screenshot
    await page.screenshot({ path: 'complete-homepage-qa.png', fullPage: true });
  });

});