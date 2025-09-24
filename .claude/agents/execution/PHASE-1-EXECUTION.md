# Claude Code Execution Prompt for Phase 1 Assessment

**COPY & PASTE THIS PROMPT TO CLAUDE CODE:**

---

I need you to coordinate a comprehensive portfolio assessment using the @knowledge-synthesizer agent. This is Phase 1 of a 2-phase improvement project.

## **Project Details:**
- Portfolio location: `C:\xampp\htdocs\ALI_PORTFOLIO\`
- Output directory: `C:\xampp\htdocs\ALI_PORTFOLIO\.claude\outputs\reports\PHASE-1\`
- Previous rollback occurred - need systematic approach
- Use @knowledge-synthesizer as main coordinator

## **Assessment Required:**

Deploy these 6 assessment agents and consolidate their findings:

### **🔵 Architecture Assessment**
- Agent: @architect-reviewer
- Focus: Design patterns, scalability, tech stack evaluation
- Output: `architecture-assessment-report.md` (save to reports/PHASE-1/)

### **🔵 Code Quality Analysis** 
- Agent: @code-reviewer
- Focus: Technical debt, code smells, security vulnerabilities
- Output: `code-quality-analysis-report.md` (save to reports/PHASE-1/)

### **🔴 Security Audit**
- Agent: @security-auditor
- Focus: Vulnerabilities, compliance gaps, access control
- Output: `security-audit-report.md` (save to reports/PHASE-1/)

### **🟡 Performance Analysis**
- Agent: @performance-engineer
- Focus: Core Web Vitals, bottlenecks, optimization opportunities
- Output: `performance-analysis-report.md` (save to reports/PHASE-1/)

### **🔵 Accessibility Compliance**
- Agent: @accessibility-tester
- Focus: WCAG 2.1 AA compliance, screen reader compatibility
- Output: `accessibility-compliance-report.md` (save to reports/PHASE-1/)

### **🟠 Design System Review**
- Agent: @ui-designer
- Focus: Visual consistency, UX patterns, brand alignment
- Output: `design-system-review-report.md` (save to reports/PHASE-1/)

## **Consolidation Required:**

After individual assessments, @knowledge-synthesizer must create:

1. **Master Assessment Report** (`master-assessment-report.md`)
   - Executive summary
   - Critical issues (Priority 1)
   - High impact issues (Priority 2)  
   - Cross-domain correlations
   - Save to: reports/PHASE-1/

2. **Priority Matrix** (`priority-matrix.json`)
   - Issues ranked by impact and urgency
   - Resource requirements
   - Dependencies mapping
   - Save to: reports/PHASE-1/

3. **Improvement Roadmap** (`improvement-roadmap.md`)
   - Phase 2 implementation strategy
   - Priority sequence
   - Quality gates
   - Success metrics
   - Save to: reports/PHASE-1/

## **Expected Output Structure:**
```
C:\xampp\htdocs\ALI_PORTFOLIO\.claude\outputs\reports\PHASE-1\
├── architecture-assessment-report.md
├── code-quality-analysis-report.md
├── security-audit-report.md
├── performance-analysis-report.md
├── accessibility-compliance-report.md
├── design-system-review-report.md
├── master-assessment-report.md
├── priority-matrix.json
└── improvement-roadmap.md
```

## **Success Criteria:**
- ✅ All 6 specialized reports completed and saved to reports/PHASE-1/
- ✅ Cross-domain issues identified
- ✅ Strategic improvement plan ready
- ✅ Clear priorities for Phase 2 implementation

**Please execute this comprehensive assessment with @knowledge-synthesizer as coordinator. All reports must be saved to the reports/PHASE-1/ subfolder.**