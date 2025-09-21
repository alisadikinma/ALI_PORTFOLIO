# Resources Directory

Frontend assets and view templates for the Laravel application.

## Structure

```
resources/
├── css/           # Stylesheets and CSS assets
├── js/            # JavaScript files and components
└── views/         # Blade templates and components
```

## CSS (`css/`)

Contains Tailwind CSS configurations and custom stylesheets:
- Tailwind utility classes for responsive design
- Custom component styles
- Typography and layout overrides

## JavaScript (`js/`)

Frontend JavaScript assets:
- Livewire components and interactions
- Custom interactive elements
- Third-party library integrations

## Views (`views/`)

Blade template engine files:

### Layout Structure
- Main layout templates with navigation and footer
- Component-based architecture
- Responsive design implementations

### Page Templates
- Homepage sections (hero, about, portfolio, contact)
- Portfolio/project detail pages
- Admin dashboard views (under `dashboard/` subdirectory)
- Authentication views (Jetstream/Livewire)

### Components
- Reusable Blade components
- Livewire reactive components
- Form components with validation

## Frontend Stack

- **Tailwind CSS** - Utility-first CSS framework
- **Livewire 3.0** - Reactive components without JavaScript
- **Vite** - Build tool for asset compilation
- **Blade** - Laravel templating engine

## Build Process

Assets are compiled using Vite:
- Development: `npm run dev`
- Production: `npm run build`

## Responsive Design

Mobile-first approach with Tailwind breakpoints:
- Mobile: Default (< 640px)
- Tablet: `md:` (768px+)
- Desktop: `lg:` (1024px+)
- Large: `xl:` (1280px+)

## Known Issues

Based on quality reviews:
- Tablet responsive breakpoints need refinement
- Mobile navigation requires fixes
- Gen Z design elements need enhancement
