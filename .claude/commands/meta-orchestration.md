# Meta-Orchestration Command
**Head of Orchestration - Comprehensive Multi-Agent Coordination**

## Output Management

### **Directory Structure**
```
.claude/outputs/
├── reports/          # Comprehensive meta-orchestration reports
├── analysis/         # Individual agent analysis files
├── artifacts/        # Screenshots, exports, generated artifacts
└── README.md         # Output documentation
```

### **Output Instructions for All Agents**
**MANDATORY**: All orchestrated agents must output to organized directories:
- Comprehensive reports → `.claude/outputs/reports/`
- Individual analysis → `.claude/outputs/analysis/`
- Screenshots/artifacts → `.claude/outputs/artifacts/`
- Use timestamp format: `YYYY-MM-DD_HH-mm-ss`
- Keep project root directory clean


Advanced orchestration system managing 8 specialized meta & orchestration subagents for complex workflows, following awesome-claude-code-subagents patterns.

## When to Use
- **Complex multi-agent tasks** requiring coordination
- **Large-scale operations** needing orchestration  
- **Workflow automation** with multiple dependencies
- **Knowledge synthesis** from various sources
- **Performance optimization** across systems
- **Error handling** in distributed workflows

## Available Subagents (8 Specialists)

### Core Coordinators
- **agent-organizer** - Multi-agent team assembly & coordination
- **multi-agent-coordinator** - Advanced distributed workflow orchestration
- **task-distributor** - Intelligent task allocation & load balancing

### Process & Workflow
- **workflow-orchestrator** - Complex business process automation
- **context-manager** - Context optimization & memory management

### Quality & Performance  
- **performance-monitor** - System performance tracking & optimization
- **error-coordinator** - Error handling & recovery strategies

### Knowledge & Intelligence
- **knowledge-synthesizer** - Multi-source information aggregation

## Orchestration Patterns

### Complex Problem Solving
```
agent-organizer → task-distributor → knowledge-synthesizer → error-coordinator
```

### Large-Scale Operations
```
multi-agent-coordinator → performance-monitor → workflow-orchestrator → context-manager
```

### Workflow Automation
```
workflow-orchestrator → task-distributor → error-coordinator → performance-monitor
```

### Knowledge Management
```
knowledge-synthesizer → context-manager → agent-organizer → workflow-orchestrator
```

## Execution Phases

### 1. Analysis Phase
- **Task decomposition** & complexity assessment
- **Agent capability mapping** & resource planning
- **Dependency analysis** & risk evaluation
- **Workflow design** & optimization opportunities

### 2. Implementation Phase
- **Team assembly** & role assignment
- **Workflow execution** & progress monitoring
- **Error handling** & recovery procedures
- **Performance optimization** & resource management

### 3. Excellence Phase
- **Result synthesis** & quality validation
- **Performance analysis** & optimization
- **Learning capture** & pattern recognition
- **Continuous improvement** & knowledge update

## Command Structure

### Basic Orchestration
```
Use meta-orchestration for [task description]:
- Complexity: [simple/moderate/complex/enterprise]
- Agents needed: [specific agents or "auto-select"]
- Performance targets: [response time, accuracy, efficiency]
- Error tolerance: [strict/moderate/flexible]
```

### Advanced Orchestration
```
Execute comprehensive orchestration:

**Task Requirements:**
- Primary objective: [clear goal]
- Subtasks: [breakdown if known]
- Dependencies: [task dependencies]
- Constraints: [time, resource, quality]

**Agent Strategy:**
- Pattern: [problem-solving/large-scale/workflow/knowledge]
- Coordination: [sequential/parallel/hybrid]
- Redundancy: [none/partial/full]
- Scaling: [fixed/dynamic/auto]

**Performance Criteria:**
- Success metrics: [specific KPIs]
- Response time: [target SLA]
- Resource limits: [CPU, memory, cost]
- Quality gates: [validation rules]

**Error Handling:**
- Tolerance level: [strict/moderate/flexible]
- Recovery strategy: [retry/fallback/compensation]
- Notification: [real-time/batch/summary]
- Learning: [capture patterns/update models]
```

## Quick Selection Guide

| Scenario | Primary Agents | Pattern |
|----------|---------------|---------|
| Complex research | knowledge-synthesizer + agent-organizer | Knowledge Management |
| Large system deployment | multi-agent-coordinator + performance-monitor | Large-Scale Operations |
| Business process automation | workflow-orchestrator + task-distributor | Workflow Automation |
| Multi-team coordination | agent-organizer + error-coordinator | Complex Problem Solving |
| Performance optimization | performance-monitor + context-manager | System Optimization |
| Error recovery design | error-coordinator + workflow-orchestrator | Resilience Engineering |

## Best Practices

### Design Principles
- **Start simple** - Build complexity incrementally
- **Monitor everything** - Visibility prevents issues  
- **Handle failures gracefully** - Expect and plan for errors
- **Optimize context usage** - Context is precious
- **Document workflows** - Complex systems need clarity

### Execution Guidelines
- **Test at scale** - Small tests miss orchestration issues
- **Version workflows** - Track changes over time
- **Measure impact** - Quantify orchestration benefits
- **Iterate continuously** - Improve based on learning
- **Collaborate effectively** - Leverage agent strengths

### Performance Targets
- **Agent selection accuracy** > 95%
- **Task completion rate** > 99%
- **Response time** < 5s for coordination
- **Resource utilization** optimized
- **Error recovery** automated
- **Cost tracking** enabled
- **Performance monitoring** continuous

## Example Usage

### Simple Task Coordination
```
Use meta-orchestration for analyzing competitor pricing strategies:
- Complexity: moderate
- Agents needed: auto-select
- Performance targets: 5min response, 95% accuracy
- Error tolerance: moderate
```

### Complex Workflow Automation
```
Execute comprehensive orchestration:

**Task Requirements:**
- Primary objective: Automate customer onboarding process
- Subtasks: data validation, account creation, welcome sequence, integration setup
- Dependencies: CRM integration, email service, document processing
- Constraints: 24h completion, GDPR compliance, 99.9% accuracy

**Agent Strategy:**
- Pattern: workflow automation
- Coordination: hybrid (parallel validation + sequential setup)
- Redundancy: partial (critical steps only)
- Scaling: dynamic based on volume

**Performance Criteria:**
- Success metrics: 99.9% completion rate, <2h average time
- Response time: Real-time status updates
- Resource limits: 4 CPU cores, 8GB RAM max
- Quality gates: Data validation, compliance checks

**Error Handling:**
- Tolerance level: strict for compliance, moderate for performance
- Recovery strategy: retry with exponential backoff, human escalation
- Notification: real-time alerts for failures
- Learning: capture failure patterns, update validation rules
```

## Integration Notes

This command integrates with all 8 Meta_Orchestration agents in the agents/ directory. Each agent maintains its specialized expertise while contributing to the overall orchestration strategy.

The system follows the three-phase execution model (Analysis → Implementation → Excellence) with comprehensive monitoring, error handling, and continuous improvement capabilities.

For maximum effectiveness, combine with other .claude commands like workflow.md for process design and agents.md for individual agent consultation.