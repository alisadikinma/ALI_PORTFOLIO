# Multi-Agent Workflow Management Command

## Command: @workflow

**Purpose**: Advanced workflow management for complex, multi-stage portfolio development tasks with custom agent coordination patterns.

## Usage

### Basic Workflow Management
```bash
@workflow [workflow-type] [task description]
```

### Available Workflow Types

#### @workflow feature [description]
**Full Feature Development Lifecycle**
- Complete feature development from planning to deployment
- Includes architecture, implementation, testing, and validation
- Coordinates all necessary agents in optimal sequence

```bash
@workflow feature Add user testimonials section with admin management
@workflow feature Implement blog functionality with categories and tags
@workflow feature Create dynamic project portfolio filtering system
```

#### @workflow optimize [target]
**Performance & Optimization Workflows**
- Systematic performance analysis and optimization
- Covers database, frontend, backend, and infrastructure
- Includes validation and performance testing

```bash
@workflow optimize mobile-performance
@workflow optimize database-queries
@workflow optimize loading-speed
@workflow optimize seo-performance
```

#### @workflow security [scope]
**Security Enhancement Workflows**
- Comprehensive security assessment and improvements
- Covers authentication, authorization, data protection, and vulnerabilities
- Includes penetration testing and compliance validation

```bash
@workflow security full-audit
@workflow security authentication-system
@workflow security data-protection
@workflow security vulnerability-assessment
```

#### @workflow quality [type]
**Quality Assurance Workflows**
- Multi-stage quality validation and improvement
- Covers code quality, design compliance, performance, and accessibility
- Includes cross-browser and device testing

```bash
@workflow quality pre-production
@workflow quality code-standards
@workflow quality accessibility-compliance
@workflow quality cross-browser-testing
```

#### @workflow design [focus]
**Design & UX Workflows**
- Comprehensive design improvement and validation
- Covers Gen Z appeal, responsiveness, accessibility, and user experience
- Includes usability testing and design system compliance

```bash
@workflow design genz-modernization
@workflow design responsive-overhaul
@workflow design accessibility-improvement
@workflow design user-experience-optimization
```

## Workflow Execution Patterns

### **Feature Development Workflow**
```
1. @laravel-specialist - Architecture planning and database design
2. @database-administrator - Database schema optimization
3. @frontend-developer - UI component development
4. @ui-designer - Design review and Gen Z appeal validation
5. @security-auditor - Security implementation review
6. @performance-engineer - Performance impact assessment
7. @code-reviewer - Code quality and best practices review
8. @qa-expert - Feature testing and validation
9. @seo-specialist - SEO impact assessment (if applicable)
10. @design-reviewer - Final design compliance validation
```

### **Performance Optimization Workflow**
```
1. @performance-engineer - Performance analysis and bottleneck identification
2. @database-optimizer - Query optimization and indexing
3. @database-administrator - Database configuration tuning
4. @frontend-developer - Asset optimization and lazy loading
5. @laravel-specialist - Backend caching and optimization
6. @security-auditor - Security impact of optimizations
7. @qa-expert - Performance testing and validation
8. @design-reviewer - UX impact assessment
```

### **Security Enhancement Workflow**
```
1. @security-auditor - Vulnerability assessment and threat modeling
2. @laravel-specialist - Security implementation and best practices
3. @database-administrator - Database security configuration
4. @frontend-developer - Client-side security implementation
5. @code-reviewer - Security code review
6. @performance-engineer - Security vs performance trade-off analysis
7. @qa-expert - Security testing and penetration testing
8. @design-reviewer - Security UX considerations
```

### **Quality Assurance Workflow**
```
1. @code-reviewer - Code quality assessment
2. @security-auditor - Security compliance check
3. @performance-engineer - Performance benchmarking
4. @design-reviewer - Design and accessibility compliance
5. @frontend-developer - Cross-browser compatibility testing
6. @ui-designer - Visual design validation
7. @seo-specialist - SEO compliance and optimization
8. @qa-expert - Final quality validation and certification
```

### **Design & UX Workflow**
```
1. @ui-designer - Design analysis and improvement recommendations
2. @design-reviewer - Accessibility and compliance assessment
3. @frontend-developer - Responsive design implementation
4. @performance-engineer - Design performance impact analysis
5. @seo-specialist - SEO impact of design changes
6. @qa-expert - Cross-device testing and validation
7. @code-reviewer - Implementation quality review
```

## Advanced Workflow Features

### **Conditional Agent Activation**
Agents are activated based on task requirements and dependencies:
- **Database Changes** → Always include Database Administrator + Optimizer
- **UI/UX Changes** → Always include UI Designer + Design Reviewer
- **Security Impact** → Always include Security Auditor
- **Performance Critical** → Always include Performance Engineer
- **New Features** → Always include Laravel Specialist + QA Expert

### **Parallel Execution Coordination**
Some agents can work in parallel while others require sequential execution:
- **Parallel**: UI Designer + Database Administrator (independent domains)
- **Sequential**: Laravel Specialist → Frontend Developer (implementation dependency)
- **Validation**: All implementation agents → QA Expert (final validation)

### **Quality Gate Management**
Automatic quality gates at key workflow stages:
- **Design Gate**: UI Designer + Design Reviewer approval before implementation
- **Security Gate**: Security Auditor approval for any security-related changes
- **Performance Gate**: Performance Engineer validation for optimization tasks
- **Quality Gate**: QA Expert final validation before completion

### **Context Propagation**
Each agent receives comprehensive context from previous agents:
- **Findings and recommendations** from previous agents
- **Dependencies and constraints** identified during execution
- **Quality requirements** and acceptance criteria
- **Integration considerations** with existing systems

## Agent Team Organization

Workflows leverage the organized agent structure:

```
Core_Development_Team/          → Primary implementation
├── laravel-specialist.md       → Backend architecture & logic
├── frontend-developer.md       → UI implementation & responsive design
└── ui-designer.md             → Visual design & Gen Z appeal

Quality_Assurance_Team/         → Quality & security validation
├── code-reviewer.md           → Code quality & best practices
├── security-auditor.md        → Security compliance & vulnerabilities
├── qa-expert.md              → Testing strategy & validation
└── performance-engineer.md    → Performance optimization

Infrastructure_n_Database/      → Data & infrastructure management
├── database-administrator.md   → Database configuration & schema
└── database-optimizer.md      → Query optimization & performance

Specialized_Experts/           → Domain-specific expertise
├── seo-specialist.md         → SEO optimization & visibility
└── design-reviewer.md        → Design compliance & accessibility
```

## Workflow Customization

### **Custom Agent Sequences**
```bash
@workflow custom "laravel-specialist -> frontend-developer -> ui-designer -> qa-expert" [task]
```

### **Agent Exclusions**
```bash
@workflow feature [task] --exclude="seo-specialist,design-reviewer"
```

### **Priority Agents**
```bash
@workflow optimize [task] --priority="performance-engineer,database-optimizer"
```

### **Parallel Execution**
```bash
@workflow quality [task] --parallel="ui-designer,database-administrator"
```

## Portfolio-Specific Workflows

### **Pre-configured for Your Project**
```bash
@workflow design genz-portfolio-refresh     # Complete Gen Z design modernization
@workflow optimize mobile-portfolio         # Mobile-specific performance optimization
@workflow security portfolio-hardening      # Portfolio security enhancement
@workflow quality portfolio-audit           # Comprehensive portfolio quality review
@workflow feature portfolio-enhancement     # New feature development workflow
```

## Integration with Orchestrator

The `@workflow` command works in conjunction with `@orchestrate`:
- **@orchestrate**: Intelligent agent selection for general tasks
- **@workflow**: Structured, predefined workflows for complex scenarios
- **Individual agents**: Direct access for specific expertise

### **When to Use Each**
- **@orchestrate**: Unknown or variable requirements, need intelligent agent selection
- **@workflow**: Known complex scenarios, need structured multi-stage execution
- **Direct agents**: Simple, focused tasks requiring specific expertise

## Example Workflow Executions

### **Complete Feature Development**
```bash
@workflow feature "Add client testimonials with rating system and admin management"

Execution Flow:
1. Laravel Specialist: Design testimonial model, relationships, and API
2. Database Administrator: Optimize testimonial database schema
3. Frontend Developer: Create testimonial display and admin components
4. UI Designer: Ensure testimonial design matches Gen Z aesthetics
5. Security Auditor: Review testimonial data handling and validation
6. Performance Engineer: Optimize testimonial loading and display
7. Code Reviewer: Review implementation for Laravel best practices
8. QA Expert: Test testimonial functionality across all devices
9. SEO Specialist: Optimize testimonials for search engine visibility
10. Design Reviewer: Final accessibility and UX validation
```

### **Performance Crisis Resolution**
```bash
@workflow optimize mobile-performance "Portfolio loads slowly on mobile devices"

Execution Flow:
1. Performance Engineer: Identify mobile performance bottlenecks
2. Database Optimizer: Optimize queries causing slow mobile loading
3. Database Administrator: Tune database configuration for mobile
4. Frontend Developer: Implement mobile-specific optimizations
5. Laravel Specialist: Add mobile-optimized caching strategies
6. Security Auditor: Ensure optimizations don't compromise security
7. QA Expert: Validate performance improvements on real devices
8. Design Reviewer: Ensure UX remains excellent post-optimization
```

## Ready for Advanced Workflows

The multi-agent workflow system provides structured, comprehensive approaches to complex portfolio development challenges. Use `@workflow` when you need systematic, multi-stage execution with guaranteed quality at every step! 🚀
