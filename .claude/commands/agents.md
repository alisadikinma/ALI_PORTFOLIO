# Individual Agent Commands

## Quick Agent Access Commands

### Core Development Agents (`agents/Core_Development_Team/`)

#### @laravel-specialist
**Laravel Framework Expert** - Laravel 10.49.0 expertise, MVC architecture, Eloquent ORM
```bash
@laravel-specialist Review my controller implementation for best practices
@laravel-specialist Help me optimize this Eloquent query
@laravel-specialist Design the backend architecture for a new feature
```

#### @frontend-developer  
**Responsive Design Expert** - Tailwind CSS, Livewire components, responsive design
```bash
@frontend-developer Fix the tablet responsive layout issues
@frontend-developer Implement a responsive navigation menu
@frontend-developer Create a mobile-first component design
```

#### @ui-designer
**Gen Z Design Expert** - Modern aesthetics, user experience, visual appeal
```bash
@ui-designer Review the current design for Gen Z appeal
@ui-designer Suggest color palette improvements
@ui-designer Design a modern hero section layout
```

### Quality Assurance Agents (`agents/Quality_Assurance_Team/`)

#### @code-reviewer
**Code Quality Expert** - Laravel best practices, maintainable code, clean architecture
```bash
@code-reviewer Review this controller for Laravel best practices
@code-reviewer Check my code for potential improvements
@code-reviewer Validate my implementation follows MVC patterns
```

#### @security-auditor
**Security Expert** - Vulnerability assessment, secure coding, authentication
```bash
@security-auditor Audit my authentication implementation
@security-auditor Review this form for security vulnerabilities  
@security-auditor Check my file upload security
```

#### @qa-expert
**Quality Assurance Expert** - Testing strategy, bug detection, validation
```bash
@qa-expert Create a testing plan for this feature
@qa-expert Review my Pest test implementation
@qa-expert Validate this feature works across devices
```

#### @performance-engineer
**Performance Expert** - Optimization, loading speed, responsiveness
```bash
@performance-engineer Analyze my portfolio's loading performance
@performance-engineer Optimize these database queries
@performance-engineer Review my image optimization strategy
```

### Infrastructure & Database Agents (`agents/Infrastructure_n_Database/`)

#### @database-administrator
**Database Expert** - MySQL configuration, schema optimization, data management
```bash
@database-administrator Review my database schema design
@database-administrator Optimize my MySQL configuration
@database-administrator Design efficient database relationships
```

#### @database-optimizer
**Query Optimization Expert** - Query performance, indexing, database efficiency
```bash
@database-optimizer Optimize this complex query
@database-optimizer Review my database indexes
@database-optimizer Analyze query performance bottlenecks
```

### Specialized Expert Agents (`agents/Specialized_Experts/`)

#### @seo-specialist
**SEO Expert** - Search optimization, portfolio visibility, meta optimization
```bash
@seo-specialist Optimize my portfolio for search engines
@seo-specialist Review my meta tags and structured data
@seo-specialist Improve my portfolio's SEO performance
```

#### @design-reviewer
**Design Compliance Expert** - Accessibility, UX validation, design standards
```bash
@design-reviewer Validate my design meets accessibility standards
@design-reviewer Review the user experience flow
@design-reviewer Check design compliance with modern standards
```

## Agent Selection Guide

### **When to Use Each Agent**

#### **For Backend/Laravel Issues:**
- `@laravel-specialist` - Core Laravel framework questions
- `@database-administrator` - Database schema and configuration
- `@database-optimizer` - Query performance issues

#### **For Frontend/UI Issues:**
- `@frontend-developer` - Responsive design and implementation
- `@ui-designer` - Visual design and Gen Z appeal
- `@design-reviewer` - Design compliance and accessibility

#### **For Quality & Security:**
- `@code-reviewer` - Code quality and best practices
- `@security-auditor` - Security vulnerabilities and compliance
- `@qa-expert` - Testing and quality validation
- `@performance-engineer` - Performance optimization

#### **For Business & Growth:**
- `@seo-specialist` - Search engine optimization

### **Agent Combinations for Common Tasks**

#### **New Feature Development:**
```bash
@laravel-specialist + @frontend-developer + @ui-designer + @code-reviewer + @qa-expert
```

#### **Performance Issues:**
```bash
@performance-engineer + @database-optimizer + @frontend-developer
```

#### **Security Concerns:**
```bash
@security-auditor + @laravel-specialist + @code-reviewer
```

#### **Design Problems:**
```bash
@ui-designer + @frontend-developer + @design-reviewer
```

## Organized Agent Structure

Your agents are now organized in logical teams for easier maintenance:

```
.claude/agents/
├── portfolio-orchestrator.md           # Master coordinator
├── Core_Development_Team/              # Development specialists
│   ├── laravel-specialist.md           # Laravel framework expertise
│   ├── frontend-developer.md           # Responsive design & UI
│   └── ui-designer.md                  # Gen Z design & aesthetics
├── Quality_Assurance_Team/             # Quality & security
│   ├── code-reviewer.md                # Code quality & best practices
│   ├── security-auditor.md             # Security & vulnerabilities
│   ├── qa-expert.md                    # Testing & validation
│   └── performance-engineer.md         # Performance optimization
├── Infrastructure_n_Database/          # Database & infrastructure
│   ├── database-administrator.md       # Database management
│   └── database-optimizer.md           # Query optimization
└── Specialized_Experts/                # Domain experts
    ├── seo-specialist.md               # SEO optimization
    └── design-reviewer.md              # Design compliance
```

## Best Practices for Agent Usage

### **1. Be Specific with Context**
❌ Bad: `@frontend-developer Fix my CSS`
✅ Good: `@frontend-developer Fix the tablet responsive layout in the project showcase section - navigation overlaps content at 768px viewport`

### **2. Provide Relevant Code/Files**
Always mention specific files, components, or code sections the agent should focus on.

### **3. Mention Your Portfolio Context**
Agents are aware of your Laravel 10.49.0 portfolio project, but specific context helps:
- Which page/component you're working on
- Current issues or constraints
- Target devices or browsers
- Performance requirements

### **4. Use Sequential Agent Calls for Complex Tasks**
For complex changes, call agents in logical sequence:
1. First: Planning agent (@laravel-specialist for backend, @ui-designer for design)
2. Then: Implementation agent (@frontend-developer, @laravel-specialist)
3. Finally: Review agents (@code-reviewer, @qa-expert)

### **5. Leverage Agent Expertise**
Each agent has deep expertise in their domain. Don't hesitate to ask for:
- Best practice recommendations
- Architecture suggestions
- Performance optimization tips
- Security considerations
- Design improvements

## Quick Reference by Team

### **Core Development Team** (Primary Implementation)
- `@laravel-specialist` - Backend architecture & Laravel expertise
- `@frontend-developer` - UI implementation & responsive design
- `@ui-designer` - Visual design & Gen Z appeal

### **Quality Assurance Team** (Quality & Security)
- `@code-reviewer` - Code quality & Laravel best practices
- `@security-auditor` - Security vulnerabilities & compliance
- `@qa-expert` - Testing strategy & cross-device validation
- `@performance-engineer` - Performance optimization & Core Web Vitals

### **Infrastructure & Database Team** (Data Management)
- `@database-administrator` - MySQL configuration & schema design
- `@database-optimizer` - Query optimization & database performance

### **Specialized Experts** (Domain Knowledge)
- `@seo-specialist` - Search engine optimization & visibility
- `@design-reviewer` - Design compliance & accessibility standards

## Integration with Orchestration Commands

### **Use @orchestrate for complex multi-agent tasks:**
```bash
@orchestrate Add advanced project filtering with search capabilities
```

### **Use @workflow for structured processes:**
```bash
@workflow feature "Add contact form with validation"
```

### **Use individual agents for focused expertise:**
```bash
@laravel-specialist Review my model relationships
@ui-designer Suggest header design improvements
```

## Maintenance Benefits

### **Organized Structure:**
- **Easy to find** the right agent for specific tasks
- **Clear team separation** between different domains
- **Scalable organization** for adding more agents later
- **Logical grouping** by expertise and responsibility

### **Better Agent Management:**
- **Team-based organization** makes maintenance easier
- **Clear hierarchy** from orchestrator to specialized agents
- **Reduced file clutter** in the agents directory
- **Professional structure** for long-term project growth

## Ready for Expert Assistance

Your agents are now organized in a professional, maintainable structure. Whether you need focused expertise from individual agents or coordinated effort from multiple teams, the system is ready to deliver exceptional results for your Laravel portfolio project! 🚀
