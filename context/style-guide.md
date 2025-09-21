# ALI_PORTFOLIO Style Guide

## Brand Identity

### Primary Colors
```css
/* Define your brand colors */
:root {
  --primary-color: #3B82F6;    /* Blue - main brand color */
  --secondary-color: #10B981;  /* Green - accent color */
  --dark-color: #1F2937;       /* Dark gray - text */
  --light-color: #F9FAFB;      /* Light gray - background */
  --white: #FFFFFF;
  --black: #000000;
}
```

### Typography
- **Primary Font**: Inter, system-ui, sans-serif
- **Heading Font**: Inter (SemiBold/Bold)
- **Body Font**: Inter (Regular/Medium)
- **Code Font**: 'Fira Code', monospace

### Font Sizes (Tailwind Scale)
- **H1**: text-4xl md:text-5xl (36px/48px)
- **H2**: text-3xl md:text-4xl (30px/36px)
- **H3**: text-2xl md:text-3xl (24px/30px)
- **H4**: text-xl md:text-2xl (20px/24px)
- **Body Large**: text-lg (18px)
- **Body Regular**: text-base (16px)
- **Body Small**: text-sm (14px)
- **Caption**: text-xs (12px)

## Layout & Spacing

### Container Widths
- **Max Width**: max-w-7xl (1280px)
- **Content Width**: max-w-4xl (896px)
- **Form Width**: max-w-lg (512px)

### Spacing Scale (Tailwind)
- **XS**: 0.25rem (4px) - p-1, m-1
- **SM**: 0.5rem (8px) - p-2, m-2
- **MD**: 1rem (16px) - p-4, m-4
- **LG**: 1.5rem (24px) - p-6, m-6
- **XL**: 2rem (32px) - p-8, m-8
- **2XL**: 3rem (48px) - p-12, m-12

### Border Radius
- **Small**: rounded-md (6px) - buttons, inputs
- **Medium**: rounded-lg (8px) - cards, modals
- **Large**: rounded-xl (12px) - hero sections
- **Full**: rounded-full - avatars, badges

## Component Patterns

### Buttons
```html
<!-- Primary Button -->
<button class="bg-primary-color hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
  Primary Action
</button>

<!-- Secondary Button -->
<button class="border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 rounded-md transition-colors">
  Secondary Action
</button>
```

### Cards
```html
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
  <!-- Card content -->
</div>
```

### Form Elements
```html
<!-- Input Field -->
<input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
```

## Portfolio-Specific Patterns

### Project Cards
- **Aspect Ratio**: 16:9 for project images
- **Hover Effects**: subtle scale (hover:scale-105)
- **Shadow**: shadow-sm to shadow-lg on hover

### Navigation
- **Header Height**: h-16 (64px)
- **Logo Size**: h-8 (32px)
- **Menu Items**: text-base, font-medium

### Hero Section
- **Background**: gradient or solid color
- **Text Alignment**: center on mobile, left on desktop
- **CTA Button**: prominent, high contrast

## Responsive Breakpoints

### Tailwind Breakpoints
- **SM**: 640px+ (tablet portrait)
- **MD**: 768px+ (tablet landscape)
- **LG**: 1024px+ (desktop)
- **XL**: 1280px+ (large desktop)
- **2XL**: 1536px+ (extra large)

### Mobile-First Design
- Stack vertically on mobile
- 2-column layout on tablet
- 3+ column layout on desktop

## Accessibility Guidelines

### Color Contrast
- **Text on Background**: minimum 4.5:1 ratio
- **Large Text**: minimum 3:1 ratio
- **Links**: distinct from body text

### Interactive Elements
- **Focus States**: visible focus rings
- **Touch Targets**: minimum 44px for mobile
- **Hover States**: clear visual feedback

## Laravel Blade Component Conventions

### File Naming
- **Components**: kebab-case (e.g., `project-card.blade.php`)
- **Views**: snake_case (e.g., `project_detail.blade.php`)

### CSS Classes
- Use Tailwind utility classes
- Custom CSS only when necessary
- Follow BEM for custom classes

## Performance Guidelines

### Images
- **Format**: WebP with JPEG fallback
- **Optimization**: compress images < 200KB
- **Lazy Loading**: use loading="lazy"

### Assets
- **CSS**: minimize custom CSS
- **JS**: use Vite for bundling
- **Fonts**: preload critical fonts

## Content Guidelines

### Writing Style
- **Tone**: Professional but approachable
- **Voice**: Active voice preferred
- **Length**: Concise, scannable content

### Portfolio Content
- **Project Descriptions**: 2-3 sentences max
- **Testimonials**: 1-2 sentences
- **About Section**: Personal but professional

---

## Usage Notes

This style guide should be referenced when:
- Creating new UI components
- Updating existing designs
- Reviewing design consistency
- Onboarding new team members

Update this guide as the design system evolves.