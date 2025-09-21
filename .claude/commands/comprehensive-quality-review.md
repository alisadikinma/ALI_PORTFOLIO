---
allowed-tools: Grep, LS, Read, Edit, MultiEdit, Write, NotebookEdit, WebFetch, TodoWrite, WebSearch, BashOutput, KillBash, ListMcpResourcesTool, ReadMcpResourceTool, mcp__playwright__browser_close, mcp__playwright__browser_resize, mcp__playwright__browser_console_messages, mcp__playwright__browser_handle_dialog, mcp__playwright__browser_evaluate, mcp__playwright__browser_file_upload, mcp__playwright__browser_install, mcp__playwright__browser_press_key, mcp__playwright__browser_type, mcp__playwright__browser_navigate, mcp__playwright__browser_navigate_back, mcp__playwright__browser_navigate_forward, mcp__playwright__browser_network_requests, mcp__playwright__browser_take_screenshot, mcp__playwright__browser_snapshot, mcp__playwright__browser_click, mcp__playwright__browser_drag, mcp__playwright__browser_hover, mcp__playwright__browser_select_option, mcp__playwright__browser_tab_list, mcp__playwright__browser_tab_new, mcp__playwright__browser_tab_select, mcp__playwright__browser_tab_close, mcp__playwright__browser_wait_for, Bash, Glob
description: Comprehensive quality review using existing MCP Playwright tools for responsive design, navigation testing, and UX validation
---

You are an expert QA engineer who conducts systematic quality reviews using the existing MCP Playwright infrastructure. Your mission is to thoroughly test the ALI_PORTFOLIO homepage addressing the issues identified in the user's uploaded screenshots and provide actionable recommendations.

## CONTEXT: KNOWN ISSUES FROM SCREENSHOTS

Based on the user's uploaded screenshots, these critical issues were identified:
1. **Tablet Responsiveness Failure**: Layout completely breaks on tablet viewport (768px)
2. **Mobile Navigation Bug**: Hamburger menu shows black overlay but no visible menu items
3. **Desktop Layout Issues**: Poor spacing and typography hierarchy

## STEP 1: VERIFY EXISTING PLAYWRIGHT MCP SETUP

Check if MCP Playwright is properly configured and screenshots are being saved to `.playwright-mcp`:

```bash
ls -la .playwright-mcp/
```

**Base URL**: `http://localhost:8000` (Laravel development server)

Ensure server is running:
```bash
php artisan serve
npm run dev
```

## STEP 2: SYSTEMATIC RESPONSIVE TESTING

### Test Critical Viewports
Test the exact viewports where issues were reported:

#### Desktop Baseline (1440x900)
```
mcp__playwright__browser_navigate http://localhost:8000
mcp__playwright__browser_resize 1440 900
mcp__playwright__browser_take_screenshot
```

#### Tablet Critical Test (768x1024) - KNOWN ISSUE
```
mcp__playwright__browser_resize 768 1024
mcp__playwright__browser_navigate http://localhost:8000
mcp__playwright__browser_take_screenshot
```
**Expected Issue**: Layout should break/look unprofessional

#### Mobile Test (375x667) - KNOWN HAMBURGER ISSUE
```
mcp__playwright__browser_resize 375 667
mcp__playwright__browser_navigate http://localhost:8000
mcp__playwright__browser_take_screenshot
```

#### Additional Responsive Tests
```
# Small mobile
mcp__playwright__browser_resize 320 568
mcp__playwright__browser_take_screenshot

# Large desktop
mcp__playwright__browser_resize 1920 1080
mcp__playwright__browser_take_screenshot
```

## STEP 3: MOBILE NAVIGATION DEBUGGING

Focus on the hamburger menu issue reported in screenshots:

### Step 3.1: Initial Mobile State
```
mcp__playwright__browser_resize 375 667
mcp__playwright__browser_navigate http://localhost:8000
mcp__playwright__browser_take_screenshot
```

### Step 3.2: Locate Hamburger Menu
Find the hamburger menu button (common selectors):
```
mcp__playwright__browser_evaluate "document.querySelector('.navbar-toggler') || document.querySelector('.hamburger') || document.querySelector('[data-bs-toggle=\"collapse\"]') || document.querySelector('button[aria-label*=\"menu\"]')"
```

### Step 3.3: Test Hamburger Menu Click
```
# Try multiple possible selectors for hamburger menu
mcp__playwright__browser_click .navbar-toggler
mcp__playwright__browser_take_screenshot

# Or try alternative selectors if first fails
mcp__playwright__browser_click .hamburger
mcp__playwright__browser_take_screenshot

# Or Bootstrap default
mcp__playwright__browser_click [data-bs-toggle="collapse"]
mcp__playwright__browser_take_screenshot
```

### Step 3.4: Check Menu State After Click
```
# Check if menu actually opened
mcp__playwright__browser_evaluate "document.querySelector('.navbar-collapse.show') || document.querySelector('.collapse.show')"

# Take screenshot to verify if menu is visible
mcp__playwright__browser_take_screenshot
```

## STEP 4: NAVIGATION LINK TESTING

Test all navigation links to ensure they work correctly:

### Step 4.1: Return to Desktop View
```
mcp__playwright__browser_resize 1440 900
mcp__playwright__browser_navigate http://localhost:8000
```

### Step 4.2: Test Each Navigation Link
Test Home, About, Gallery, Portfolio, Services links:

```
# Test About link
mcp__playwright__browser_click a[href*="about"]
mcp__playwright__browser_take_screenshot
mcp__playwright__browser_navigate_back

# Test Portfolio link  
mcp__playwright__browser_click a[href*="portfolio"]
mcp__playwright__browser_take_screenshot
mcp__playwright__browser_navigate_back

# Test Gallery link
mcp__playwright__browser_click a[href*="gallery"]
mcp__playwright__browser_take_screenshot
mcp__playwright__browser_navigate_back

# Test Services link
mcp__playwright__browser_click a[href*="services"]
mcp__playwright__browser_take_screenshot
mcp__playwright__browser_navigate_back
```

## STEP 5: SCROLL BEHAVIOR & LAYOUT TESTING

### Step 5.1: Test Scroll on Different Viewports
```
# Desktop scroll test
mcp__playwright__browser_resize 1440 900
mcp__playwright__browser_navigate http://localhost:8000

# Scroll to middle
mcp__playwright__browser_evaluate "window.scrollTo(0, document.body.scrollHeight / 2)"
mcp__playwright__browser_take_screenshot

# Scroll to bottom
mcp__playwright__browser_evaluate "window.scrollTo(0, document.body.scrollHeight)"
mcp__playwright__browser_take_screenshot

# Back to top
mcp__playwright__browser_evaluate "window.scrollTo(0, 0)"
```

### Step 5.2: Test Scroll on Problem Viewport (Tablet)
```
mcp__playwright__browser_resize 768 1024
mcp__playwright__browser_navigate http://localhost:8000

# Test if scrolling reveals layout issues
mcp__playwright__browser_evaluate "window.scrollTo(0, document.body.scrollHeight / 2)"
mcp__playwright__browser_take_screenshot
```

## STEP 6: INTERACTIVE ELEMENT TESTING

### Step 6.1: Test Button Interactions
```
mcp__playwright__browser_resize 1440 900
mcp__playwright__browser_navigate http://localhost:8000

# Find and test primary CTA buttons
mcp__playwright__browser_hover .btn-primary
mcp__playwright__browser_take_screenshot

# Test contact button or similar
mcp__playwright__browser_hover .cta-button
mcp__playwright__browser_take_screenshot
```

### Step 6.2: Test Form Elements (if any)
```
# Click on contact form inputs if they exist
mcp__playwright__browser_click input[type="text"]
mcp__playwright__browser_type "Test Input"
mcp__playwright__browser_take_screenshot

mcp__playwright__browser_click textarea
mcp__playwright__browser_type "Test message content"
mcp__playwright__browser_take_screenshot
```

## STEP 7: CONSOLE ERROR DETECTION

Check for JavaScript errors that might be causing the navigation issues:

```
mcp__playwright__browser_navigate http://localhost:8000
mcp__playwright__browser_console_messages
```

## STEP 8: PERFORMANCE & LOADING ANALYSIS

```
mcp__playwright__browser_navigate http://localhost:8000
mcp__playwright__browser_evaluate "performance.getEntriesByType('navigation')[0]"
```

## STEP 9: GEN Z DESIGN ASSESSMENT

Based on existing genz-homepage-review command, evaluate Gen Z appeal:

### Color & Typography Analysis
```
mcp__playwright__browser_evaluate "
const colors = [];
const fonts = [];
document.querySelectorAll('*').forEach(el => {
  const styles = getComputedStyle(el);
  if (styles.backgroundColor !== 'rgba(0, 0, 0, 0)') colors.push(styles.backgroundColor);
  if (styles.fontFamily) fonts.push(styles.fontFamily);
});
{colors: [...new Set(colors)].slice(0,10), fonts: [...new Set(fonts)].slice(0,5)}
"
```

### Mobile-First Assessment
```
mcp__playwright__browser_resize 375 667
mcp__playwright__browser_evaluate "
const buttons = Array.from(document.querySelectorAll('button, .btn'));
const touchFriendly = buttons.filter(btn => {
  const rect = btn.getBoundingClientRect();
  return rect.width >= 44 && rect.height >= 44;
});
{totalButtons: buttons.length, touchFriendlyButtons: touchFriendly.length}
"
```

## STEP 10: ISSUE DOCUMENTATION & RECOMMENDATIONS

Document findings in this structured format:

### CRITICAL ISSUES (Must Fix Immediately)
1. **Tablet Layout Breakdown**: Specific elements that break at 768px
2. **Mobile Navigation Failure**: Hamburger menu black overlay issue
3. **JavaScript Errors**: Any console errors blocking functionality

### MEDIUM PRIORITY ISSUES  
1. **Responsive Breakpoints**: Specific CSS issues at different viewports
2. **Touch Target Sizes**: Buttons smaller than 44px on mobile
3. **Visual Hierarchy**: Typography and spacing inconsistencies

### LOW PRIORITY ISSUES
1. **Performance Optimizations**: Loading time improvements
2. **Gen Z Appeal**: Color, typography, and interaction enhancements

### ACTIONABLE SOLUTIONS
For each issue, provide:
1. **Root Cause**: Why the issue occurs
2. **Specific Fix**: Exact CSS/HTML changes needed
3. **File Location**: Which files to modify
4. **Test Verification**: How to verify the fix works

## STEP 11: COMPARISON WITH PREVIOUS RESULTS

Compare new screenshots with existing ones in `.playwright-mcp` folder:
- `homepage-desktop-1440px.png`
- `homepage-mobile-375px.png`  
- `homepage-tablet-768px.png`
- `homepage-genz-updated-*.png`

Document what changed and whether issues were resolved.

## OUTPUT FORMAT

Provide comprehensive analysis with:

1. **Executive Summary**: Overall quality score and critical issues
2. **Responsive Design Analysis**: Specific breakpoint failures
3. **Navigation Functionality Report**: What works/doesn't work
4. **Gen Z Appeal Assessment**: Modern design compliance score
5. **Priority Fix List**: Ordered by impact and effort
6. **Implementation Plan**: Step-by-step code changes

Focus on providing specific, actionable solutions that can be immediately implemented to resolve the identified responsiveness and navigation issues.
