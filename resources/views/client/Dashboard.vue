<template>
  <div class="dashboard-wrapper">
    <Navbar />

    <div class="dashboard-container">
      <!-- Header -->
      <div class="dashboard-header">
        <div>
          <p class="dashboard-eyebrow">PrimeOutsourcing Portal</p>
          <h1 class="dashboard-title">Client Dashboard</h1>
          <p class="dashboard-subtitle">Welcome back, {{ userName }} — here's your project overview.</p>
        </div>
        <div class="header-meta">
          <span class="date-badge">{{ today }}</span>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="loading-state">
        <div class="spinner"></div>
        <p>Loading your projects...</p>
      </div>

      <template v-else>
        <!-- KPI Cards -->
        <div class="kpi-grid">
          <div class="kpi-card kpi-total">
            <div class="kpi-icon">📁</div>
            <div class="kpi-value">{{ stats.totalprojects }}</div>
            <div class="kpi-label">Total Projects</div>
          </div>
          <div class="kpi-card kpi-active">
            <div class="kpi-icon">⚡</div>
            <div class="kpi-value">{{ stats.activeprojects }}</div>
            <div class="kpi-label">Active</div>
          </div>
          <div class="kpi-card kpi-completed">
            <div class="kpi-icon">✅</div>
            <div class="kpi-value">{{ stats.completedprojects }}</div>
            <div class="kpi-label">Completed</div>
          </div>
          <div class="kpi-card kpi-pending">
            <div class="kpi-icon">🕐</div>
            <div class="kpi-value">{{ stats.pendingprojects }}</div>
            <div class="kpi-label">Pending</div>
          </div>
          <div class="kpi-card kpi-budget">
            <div class="kpi-icon">💰</div>
            <div class="kpi-value">{{ formatBudget(stats.totalbudgetspent) }}</div>
            <div class="kpi-label">Total Budget</div>
          </div>
          <div class="kpi-card kpi-urgent">
            <div class="kpi-icon">🚨</div>
            <div class="kpi-value">{{ urgentCount }}</div>
            <div class="kpi-label">Urgent (≤7 days)</div>
          </div>
        </div>

        <!-- Main Content Grid -->
        <div class="main-grid">

          <!-- Project Table -->
          <div class="panel projects-panel">
            <div class="panel-header">
              <h2 class="panel-title">All Projects</h2>
              <div class="filter-tabs">
                <button
                  v-for="tab in filterTabs"
                  :key="tab.value"
                  :class="['tab-btn', { active: activeFilter === tab.value }]"
                  @click="activeFilter = tab.value"
                >{{ tab.label }}</button>
              </div>
            </div>

            <div class="table-wrapper">
              <table class="project-table">
                <thead>
                  <tr>
                    <th>Project</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Target Date</th>
                    <th>Urgency</th>
                    <th>Budget</th>
                    <th>Developers</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="project in filteredProjects"
                    :key="project.id"
                    class="project-row"
                    @click="openProject(project)"
                  >
                    <td>
                      <div class="project-name">{{ project.title }}</div>
                      <div class="project-desc">{{ truncate(project.description, 40) }}</div>
                    </td>
                    <td>
                      <span :class="['status-badge', `status-${project.status}`]">
                        {{ project.status }}
                      </span>
                    </td>
                    <td>
                      <div class="progress-bar-wrap">
                        <div class="progress-bar">
                          <div
                            class="progress-fill"
                            :style="{ width: project.progress + '%' }"
                            :class="progressClass(project.progress)"
                          ></div>
                        </div>
                        <span class="progress-pct">{{ project.progress ?? 0 }}%</span>
                      </div>
                    </td>
                    <td>
                      <div class="date-cell">
                        <span class="date-val">{{ formatDate(project.deadline) }}</span>
                        <span class="days-left" :class="daysLeftClass(project.deadline)">
                          {{ daysLeftLabel(project.deadline) }}
                        </span>
                      </div>
                    </td>
                    <td>
                      <span :class="['urgency-badge', urgencyClass(project.deadline, project.status)]">
                        {{ urgencyLabel(project.deadline, project.status) }}
                      </span>
                    </td>
                    <td class="budget-cell">{{ formatBudget(project.budget) }}</td>
                    <td>
                      <div class="dev-avatars">
                        <span
                          v-for="(asgn, i) in (project.assignments || []).slice(0, 3)"
                          :key="i"
                          class="dev-avatar"
                          :title="asgn.developer?.name"
                        >
                          {{ initials(asgn.developer?.name) }}
                        </span>
                        <span
                          v-if="(project.assignments || []).length > 3"
                          class="dev-avatar dev-more"
                        >+{{ project.assignments.length - 3 }}</span>
                      </div>
                    </td>
                    <td>
                      <button class="view-btn" @click.stop="openProject(project)">View →</button>
                    </td>
                  </tr>
                  <tr v-if="filteredProjects.length === 0">
                    <td colspan="8" class="empty-row">No projects found.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Right Column -->
          <div class="right-col">

            <!-- Deadline Calendar -->
            <div class="panel calendar-panel">
              <div class="panel-header">
                <h2 class="panel-title">📅 Deadline Calendar</h2>
              </div>
              <div class="calendar-months">
                <div class="calendar-nav">
                  <button class="cal-nav-btn" @click="prevMonth">‹</button>
                  <span class="cal-month-label">{{ calMonthLabel }}</span>
                  <button class="cal-nav-btn" @click="nextMonth">›</button>
                </div>
                <div class="cal-grid">
                  <div class="cal-day-name" v-for="d in ['Su','Mo','Tu','We','Th','Fr','Sa']" :key="d">{{ d }}</div>
                  <div
                    v-for="(day, idx) in calDays"
                    :key="idx"
                    :class="['cal-day', {
                      'cal-empty': !day,
                      'cal-today': day && isToday(day),
                      'cal-has-deadline': day && deadlineDays.includes(day.getDate()),
                    }]"
                    :title="day && deadlineDays.includes(day.getDate()) ? deadlineTooltip(day) : ''"
                  >
                    <span v-if="day">{{ day.getDate() }}</span>
                    <span v-if="day && deadlineDays.includes(day.getDate())" class="cal-dot"></span>
                  </div>
                </div>
              </div>

              <!-- Upcoming Deadlines List -->
              <div class="upcoming-list">
                <p class="upcoming-title">Upcoming Deadlines</p>
                <div
                  v-for="proj in upcomingDeadlines"
                  :key="proj.id"
                  class="upcoming-item"
                  @click="openProject(proj)"
                >
                  <div :class="['upcoming-dot', urgencyClass(proj.deadline, proj.status)]"></div>
                  <div class="upcoming-info">
                    <span class="upcoming-name">{{ proj.title }}</span>
                    <span class="upcoming-date">{{ formatDate(proj.deadline) }}</span>
                  </div>
                  <span class="upcoming-days" :class="daysLeftClass(proj.deadline)">
                    {{ daysLeftLabel(proj.deadline) }}
                  </span>
                </div>
                <p v-if="upcomingDeadlines.length === 0" class="no-deadlines">No upcoming deadlines.</p>
              </div>
            </div>

            <!-- Urgency Summary -->
            <div class="panel urgency-panel">
              <div class="panel-header">
                <h2 class="panel-title">🚨 Urgency Overview</h2>
              </div>
              <div class="urgency-list">
                <div class="urgency-row" v-for="u in urgencySummary" :key="u.label">
                  <div class="urgency-label-wrap">
                    <span :class="['urgency-dot', u.cls]"></span>
                    <span class="urgency-text">{{ u.label }}</span>
                  </div>
                  <div class="urgency-bar-wrap">
                    <div class="urgency-bar">
                      <div
                        :class="['urgency-fill', u.cls]"
                        :style="{ width: (stats.totalprojects > 0 ? (u.count / stats.totalprojects) * 100 : 0) + '%' }"
                      ></div>
                    </div>
                    <span class="urgency-count">{{ u.count }}</span>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </template>
    </div>

    <!-- Project Detail Modal -->
    <div v-if="selectedProject" class="modal-overlay" @click.self="selectedProject = null">
      <div class="modal">
        <div class="modal-header">
          <div>
            <h2 class="modal-title">{{ selectedProject.title }}</h2>
            <span :class="['status-badge', `status-${selectedProject.status}`]">{{ selectedProject.status }}</span>
          </div>
          <button class="modal-close" @click="selectedProject = null">✕</button>
        </div>
        <div class="modal-body">
          <div class="modal-meta">
            <div class="meta-item">
              <span class="meta-label">Start Date</span>
              <span class="meta-val">{{ formatDate(selectedProject.startdate) }}</span>
            </div>
            <div class="meta-item">
              <span class="meta-label">Deadline</span>
              <span class="meta-val deadline-val">{{ formatDate(selectedProject.deadline) }}</span>
            </div>
            <div class="meta-item">
              <span class="meta-label">Budget</span>
              <span class="meta-val">{{ formatBudget(selectedProject.budget) }}</span>
            </div>
            <div class="meta-item">
              <span class="meta-label">Urgency</span>
              <span :class="['urgency-badge', urgencyClass(selectedProject.deadline, selectedProject.status)]">
                {{ urgencyLabel(selectedProject.deadline, selectedProject.status) }}
              </span>
            </div>
          </div>
          <p class="modal-desc">{{ selectedProject.description || 'No description provided.' }}</p>

          <div class="modal-section">
            <h3 class="modal-section-title">Overall Progress</h3>
            <div class="progress-bar big-progress">
              <div
                :class="['progress-fill', progressClass(selectedProject.progress)]"
                :style="{ width: (selectedProject.progress ?? 0) + '%' }"
              ></div>
            </div>
            <p class="progress-label">{{ selectedProject.progress ?? 0 }}% complete</p>
          </div>

          <div class="modal-section" v-if="selectedProject.assignments && selectedProject.assignments.length">
            <h3 class="modal-section-title">Assigned Developers</h3>
            <div class="dev-list">
              <div
                v-for="asgn in selectedProject.assignments"
                :key="asgn.id"
                class="dev-row"
              >
                <span class="dev-avatar large-avatar">{{ initials(asgn.developer?.name) }}</span>
                <div>
                  <p class="dev-name">{{ asgn.developer?.name }}</p>
                  <p class="dev-role">{{ asgn.developer?.email }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-section" v-if="selectedProject.tasks && selectedProject.tasks.length">
            <h3 class="modal-section-title">Tasks ({{ selectedProject.tasks.length }})</h3>
            <div class="task-list">
              <div
                v-for="task in selectedProject.tasks"
                :key="task.id"
                class="task-row"
              >
                <span :class="['task-status-dot', `task-${task.status}`]"></span>
                <span class="task-name">{{ task.title }}</span>
                <span :class="['status-badge', `status-${task.status}`]" style="font-size:0.7rem">{{ task.status }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Navbar from '../../components/Navbar.vue';
import axios from 'axios';

// ── State ────────────────────────────────────────────────
const loading = ref(true);
const stats = ref({
  totalprojects: 0,
  activeprojects: 0,
  completedprojects: 0,
  pendingprojects: 0,
  totalbudgetspent: 0,
});
const projects = ref([]);
const selectedProject = ref(null);
const activeFilter = ref('all');
const calendarDate = ref(new Date());

// ── Fetch ────────────────────────────────────────────────
onMounted(async () => {
  try {
    const res = await axios.get('/api/client/dashboard');
    stats.value = res.data.stats;
    projects.value = res.data.projects;
  } catch (e) {
    console.error('Dashboard load error:', e);
  } finally {
    loading.value = false;
  }
});

// ── User ─────────────────────────────────────────────────
const userName = computed(() => {
  // Attempt to get from meta or first project client name
  return window.__USER_NAME__ || 'Client';
});

const today = computed(() => new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }));

// ── Filters ──────────────────────────────────────────────
const filterTabs = [
  { label: 'All', value: 'all' },
  { label: 'Active', value: 'active' },
  { label: 'Pending', value: 'pending' },
  { label: 'Completed', value: 'completed' },
];

const filteredProjects = computed(() => {
  if (activeFilter.value === 'all') return projects.value;
  return projects.value.filter(p => p.status === activeFilter.value);
});

// ── Urgency ───────────────────────────────────────────────
function daysUntil(deadline) {
  if (!deadline) return null;
  const diff = new Date(deadline) - new Date();
  return Math.ceil(diff / (1000 * 60 * 60 * 24));
}

function urgencyLabel(deadline, status) {
  if (status === 'completed') return 'Done';
  const d = daysUntil(deadline);
  if (d === null) return 'No date';
  if (d < 0) return 'Overdue';
  if (d <= 3) return 'Critical';
  if (d <= 7) return 'Urgent';
  if (d <= 14) return 'Due Soon';
  return 'On Track';
}

function urgencyClass(deadline, status) {
  if (status === 'completed') return 'urg-done';
  const d = daysUntil(deadline);
  if (d === null) return 'urg-none';
  if (d < 0) return 'urg-overdue';
  if (d <= 3) return 'urg-critical';
  if (d <= 7) return 'urg-urgent';
  if (d <= 14) return 'urg-soon';
  return 'urg-ok';
}

function daysLeftLabel(deadline) {
  const d = daysUntil(deadline);
  if (d === null) return '';
  if (d < 0) return `${Math.abs(d)}d overdue`;
  if (d === 0) return 'Due today';
  return `${d}d left`;
}

function daysLeftClass(deadline) {
  const d = daysUntil(deadline);
  if (d === null) return '';
  if (d < 0) return 'days-overdue';
  if (d <= 7) return 'days-urgent';
  return 'days-ok';
}

const urgentCount = computed(() =>
  projects.value.filter(p => {
    const d = daysUntil(p.deadline);
    return p.status !== 'completed' && d !== null && d <= 7 && d >= 0;
  }).length
);

const urgencySummary = computed(() => {
  const counts = { overdue: 0, critical: 0, urgent: 0, soon: 0, ok: 0, done: 0 };
  projects.value.forEach(p => {
    if (p.status === 'completed') { counts.done++; return; }
    const d = daysUntil(p.deadline);
    if (d === null) return;
    if (d < 0) counts.overdue++;
    else if (d <= 3) counts.critical++;
    else if (d <= 7) counts.urgent++;
    else if (d <= 14) counts.soon++;
    else counts.ok++;
  });
  return [
    { label: 'Overdue', count: counts.overdue, cls: 'urg-overdue' },
    { label: 'Critical (≤3 days)', count: counts.critical, cls: 'urg-critical' },
    { label: 'Urgent (≤7 days)', count: counts.urgent, cls: 'urg-urgent' },
    { label: 'Due Soon (≤14 days)', count: counts.soon, cls: 'urg-soon' },
    { label: 'On Track', count: counts.ok, cls: 'urg-ok' },
    { label: 'Completed', count: counts.done, cls: 'urg-done' },
  ];
});

// ── Calendar ──────────────────────────────────────────────
const calMonthLabel = computed(() =>
  calendarDate.value.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
);

const calDays = computed(() => {
  const d = new Date(calendarDate.value.getFullYear(), calendarDate.value.getMonth(), 1);
  const firstDay = d.getDay();
  const daysInMonth = new Date(calendarDate.value.getFullYear(), calendarDate.value.getMonth() + 1, 0).getDate();
  const cells = [];
  for (let i = 0; i < firstDay; i++) cells.push(null);
  for (let i = 1; i <= daysInMonth; i++) {
    cells.push(new Date(calendarDate.value.getFullYear(), calendarDate.value.getMonth(), i));
  }
  return cells;
});

const deadlineDays = computed(() => {
  return projects.value
    .filter(p => p.deadline)
    .map(p => new Date(p.deadline))
    .filter(d =>
      d.getFullYear() === calendarDate.value.getFullYear() &&
      d.getMonth() === calendarDate.value.getMonth()
    )
    .map(d => d.getDate());
});

function deadlineTooltip(day) {
  return projects.value
    .filter(p => {
      if (!p.deadline) return false;
      const d = new Date(p.deadline);
      return d.getFullYear() === day.getFullYear() &&
             d.getMonth() === day.getMonth() &&
             d.getDate() === day.getDate();
    })
    .map(p => p.title)
    .join(', ');
}

function isToday(day) {
  const t = new Date();
  return day.getDate() === t.getDate() &&
    day.getMonth() === t.getMonth() &&
    day.getFullYear() === t.getFullYear();
}

function prevMonth() {
  calendarDate.value = new Date(calendarDate.value.getFullYear(), calendarDate.value.getMonth() - 1, 1);
}
function nextMonth() {
  calendarDate.value = new Date(calendarDate.value.getFullYear(), calendarDate.value.getMonth() + 1, 1);
}

const upcomingDeadlines = computed(() =>
  [...projects.value]
    .filter(p => p.deadline && p.status !== 'completed')
    .sort((a, b) => new Date(a.deadline) - new Date(b.deadline))
    .slice(0, 5)
);

// ── Helpers ───────────────────────────────────────────────
function formatDate(date) {
  if (!date) return '—';
  return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function formatBudget(val) {
  if (!val && val !== 0) return '—';
  return '₱' + Number(val).toLocaleString('en-PH', { minimumFractionDigits: 2 });
}

function truncate(str, len) {
  if (!str) return '';
  return str.length > len ? str.slice(0, len) + '…' : str;
}

function initials(name) {
  if (!name) return '?';
  return name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
}

function progressClass(pct) {
  if (!pct) return 'prog-low';
  if (pct >= 75) return 'prog-high';
  if (pct >= 40) return 'prog-mid';
  return 'prog-low';
}

function openProject(project) {
  selectedProject.value = project;
}
</script>

<style scoped>
/* ── Tokens ── */
:root {
  --po-blue: #1a3c6e;
  --po-blue-light: #2a5298;
  --po-accent: #0ea5e9;
  --po-accent-soft: #e0f2fe;
  --po-surface: #ffffff;
  --po-bg: #f0f4f8;
  --po-border: #dde3ea;
  --po-text: #1e2a38;
  --po-muted: #6b7a8f;
  --po-danger: #ef4444;
  --po-warning: #f59e0b;
  --po-success: #10b981;
  --po-info: #3b82f6;
  --radius: 12px;
}

/* ── Layout ── */
.dashboard-wrapper {
  min-height: 100vh;
  background: var(--po-bg);
  font-family: 'Inter', system-ui, sans-serif;
  color: var(--po-text);
}

.dashboard-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 2rem 1.5rem 4rem;
}

/* ── Header ── */
.dashboard-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.dashboard-eyebrow {
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--po-accent);
  margin: 0 0 0.25rem;
}

.dashboard-title {
  font-size: 2rem;
  font-weight: 800;
  color: var(--po-blue);
  margin: 0 0 0.25rem;
}

.dashboard-subtitle {
  color: var(--po-muted);
  font-size: 0.92rem;
  margin: 0;
}

.date-badge {
  background: var(--po-blue);
  color: white;
  padding: 0.4rem 1rem;
  border-radius: 999px;
  font-size: 0.8rem;
  font-weight: 500;
}

/* ── Loading ── */
.loading-state {
  text-align: center;
  padding: 4rem;
  color: var(--po-muted);
}
.spinner {
  width: 36px;
  height: 36px;
  border: 3px solid var(--po-border);
  border-top-color: var(--po-accent);
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
  margin: 0 auto 1rem;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── KPI Grid ── */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 1rem;
  margin-bottom: 2rem;
}

@media (max-width: 1100px) { .kpi-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }

.kpi-card {
  background: var(--po-surface);
  border-radius: var(--radius);
  padding: 1.25rem 1rem;
  text-align: center;
  box-shadow: 0 1px 4px rgba(0,0,0,0.06);
  border-top: 4px solid transparent;
  transition: transform 0.15s, box-shadow 0.15s;
}
.kpi-card:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,0.1); }

.kpi-total  { border-top-color: var(--po-blue); }
.kpi-active { border-top-color: var(--po-accent); }
.kpi-completed { border-top-color: var(--po-success); }
.kpi-pending { border-top-color: var(--po-warning); }
.kpi-budget { border-top-color: #8b5cf6; }
.kpi-urgent { border-top-color: var(--po-danger); }

.kpi-icon  { font-size: 1.5rem; margin-bottom: 0.4rem; }
.kpi-value { font-size: 1.8rem; font-weight: 800; color: var(--po-blue); }
.kpi-label { font-size: 0.72rem; font-weight: 600; color: var(--po-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.15rem; }

/* ── Main Grid ── */
.main-grid {
  display: grid;
  grid-template-columns: 1fr 360px;
  gap: 1.5rem;
  align-items: start;
}

@media (max-width: 1024px) { .main-grid { grid-template-columns: 1fr; } }

/* ── Panel ── */
.panel {
  background: var(--po-surface);
  border-radius: var(--radius);
  box-shadow: 0 1px 4px rgba(0,0,0,0.07);
  overflow: hidden;
}

.panel-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.1rem 1.5rem;
  border-bottom: 1px solid var(--po-border);
  flex-wrap: wrap;
  gap: 0.75rem;
}

.panel-title {
  font-size: 1rem;
  font-weight: 700;
  color: var(--po-blue);
  margin: 0;
}

/* ── Filter Tabs ── */
.filter-tabs { display: flex; gap: 0.25rem; }
.tab-btn {
  padding: 0.3rem 0.75rem;
  border-radius: 999px;
  font-size: 0.78rem;
  font-weight: 600;
  border: none;
  background: var(--po-bg);
  color: var(--po-muted);
  cursor: pointer;
  transition: background 0.15s, color 0.15s;
}
.tab-btn.active {
  background: var(--po-blue);
  color: white;
}

/* ── Table ── */
.table-wrapper { overflow-x: auto; }

.project-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.85rem;
}

.project-table th {
  padding: 0.7rem 1rem;
  text-align: left;
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--po-muted);
  background: var(--po-bg);
  border-bottom: 1px solid var(--po-border);
  white-space: nowrap;
}

.project-row {
  cursor: pointer;
  transition: background 0.12s;
}
.project-row:hover td { background: #f8fbff; }

.project-table td {
  padding: 0.85rem 1rem;
  border-bottom: 1px solid var(--po-border);
  vertical-align: middle;
}

.project-name { font-weight: 600; color: var(--po-text); }
.project-desc { font-size: 0.75rem; color: var(--po-muted); margin-top: 0.1rem; }

/* ── Status Badges ── */
.status-badge {
  display: inline-block;
  padding: 0.2rem 0.65rem;
  border-radius: 999px;
  font-size: 0.72rem;
  font-weight: 700;
  text-transform: capitalize;
}
.status-active     { background: #dbeafe; color: #1d4ed8; }
.status-completed  { background: #d1fae5; color: #065f46; }
.status-pending    { background: #fef3c7; color: #92400e; }
.status-on_hold    { background: #f3f4f6; color: #374151; }
.status-cancelled  { background: #fee2e2; color: #991b1b; }

/* ── Progress ── */
.progress-bar-wrap { display: flex; align-items: center; gap: 0.5rem; min-width: 110px; }
.progress-bar {
  flex: 1;
  height: 6px;
  background: var(--po-border);
  border-radius: 999px;
  overflow: hidden;
}
.progress-fill { height: 100%; border-radius: 999px; transition: width 0.4s; }
.prog-high { background: var(--po-success); }
.prog-mid  { background: var(--po-accent); }
.prog-low  { background: var(--po-warning); }
.progress-pct { font-size: 0.72rem; color: var(--po-muted); white-space: nowrap; }

.big-progress { height: 10px; margin-bottom: 0.4rem; }
.progress-label { font-size: 0.8rem; color: var(--po-muted); }

/* ── Date ── */
.date-cell { display: flex; flex-direction: column; gap: 0.15rem; }
.date-val { font-weight: 500; white-space: nowrap; }
.days-left { font-size: 0.72rem; font-weight: 600; }
.days-overdue { color: var(--po-danger); }
.days-urgent  { color: var(--po-warning); }
.days-ok      { color: var(--po-success); }

/* ── Urgency ── */
.urgency-badge {
  display: inline-block;
  padding: 0.2rem 0.65rem;
  border-radius: 999px;
  font-size: 0.7rem;
  font-weight: 700;
  white-space: nowrap;
}
.urg-overdue  { background: #fee2e2; color: #991b1b; }
.urg-critical { background: #fde8d8; color: #c2410c; }
.urg-urgent   { background: #fef3c7; color: #92400e; }
.urg-soon     { background: #e0f2fe; color: #0369a1; }
.urg-ok       { background: #d1fae5; color: #065f46; }
.urg-done     { background: #f3f4f6; color: #6b7280; }
.urg-none     { background: #f9fafb; color: #9ca3af; }

/* ── Budget ── */
.budget-cell { font-weight: 600; white-space: nowrap; }

/* ── Dev Avatars ── */
.dev-avatars { display: flex; gap: 0.2rem; }
.dev-avatar {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: var(--po-blue);
  color: white;
  font-size: 0.65rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
}
.dev-more { background: var(--po-muted); }

/* ── View Btn ── */
.view-btn {
  background: none;
  border: 1px solid var(--po-border);
  color: var(--po-blue);
  font-size: 0.78rem;
  font-weight: 600;
  padding: 0.3rem 0.7rem;
  border-radius: 6px;
  cursor: pointer;
  white-space: nowrap;
  transition: background 0.12s;
}
.view-btn:hover { background: var(--po-accent-soft); }

.empty-row { text-align: center; color: var(--po-muted); padding: 2rem; }

/* ── Right Column ── */
.right-col { display: flex; flex-direction: column; gap: 1.5rem; }

/* ── Calendar ── */
.calendar-panel {}

.calendar-nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.75rem 1.25rem 0.5rem;
}
.cal-month-label { font-weight: 700; font-size: 0.9rem; color: var(--po-blue); }
.cal-nav-btn {
  background: none;
  border: 1px solid var(--po-border);
  color: var(--po-blue);
  font-size: 1rem;
  width: 28px;
  height: 28px;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}
.cal-nav-btn:hover { background: var(--po-accent-soft); }

.cal-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  padding: 0 1rem 1rem;
  gap: 2px;
}

.cal-day-name {
  text-align: center;
  font-size: 0.65rem;
  font-weight: 700;
  color: var(--po-muted);
  padding: 0.3rem 0;
}

.cal-day {
  position: relative;
  text-align: center;
  font-size: 0.8rem;
  padding: 0.35rem 0.2rem;
  border-radius: 6px;
  cursor: default;
  min-height: 34px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.cal-empty {}

.cal-today {
  background: var(--po-blue);
  color: white;
  font-weight: 700;
}

.cal-has-deadline {
  background: #fff4e0;
  font-weight: 600;
  cursor: pointer;
}

.cal-today.cal-has-deadline {
  background: var(--po-blue);
  color: white;
}

.cal-dot {
  width: 5px;
  height: 5px;
  border-radius: 50%;
  background: var(--po-warning);
  margin-top: 2px;
}
.cal-today .cal-dot { background: white; }

/* ── Upcoming Deadlines ── */
.upcoming-list {
  padding: 0.75rem 1.25rem 1.25rem;
  border-top: 1px solid var(--po-border);
}

.upcoming-title {
  font-size: 0.72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  color: var(--po-muted);
  margin: 0 0 0.75rem;
}

.upcoming-item {
  display: flex;
  align-items: center;
  gap: 0.65rem;
  padding: 0.55rem 0;
  border-bottom: 1px solid var(--po-border);
  cursor: pointer;
  transition: background 0.1s;
}
.upcoming-item:last-child { border-bottom: none; }
.upcoming-item:hover { background: #f8fbff; border-radius: 6px; padding-left: 0.3rem; }

.upcoming-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  flex-shrink: 0;
}

.upcoming-info {
  flex: 1;
  min-width: 0;
}
.upcoming-name {
  display: block;
  font-size: 0.82rem;
  font-weight: 600;
  color: var(--po-text);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.upcoming-date { font-size: 0.72rem; color: var(--po-muted); }

.upcoming-days {
  font-size: 0.72rem;
  font-weight: 700;
  white-space: nowrap;
  padding: 0.15rem 0.4rem;
  border-radius: 4px;
  background: var(--po-bg);
}

.no-deadlines { font-size: 0.82rem; color: var(--po-muted); text-align: center; padding: 1rem 0; }

/* ── Urgency Panel ── */
.urgency-panel {}
.urgency-list { padding: 1rem 1.25rem; display: flex; flex-direction: column; gap: 0.75rem; }

.urgency-row { display: flex; align-items: center; gap: 0.75rem; justify-content: space-between; }
.urgency-label-wrap { display: flex; align-items: center; gap: 0.5rem; min-width: 140px; }
.urgency-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.urgency-text { font-size: 0.8rem; color: var(--po-text); }

.urgency-bar-wrap { display: flex; align-items: center; gap: 0.5rem; flex: 1; }
.urgency-bar {
  flex: 1;
  height: 6px;
  background: var(--po-bg);
  border-radius: 999px;
  overflow: hidden;
}
.urgency-fill { height: 100%; border-radius: 999px; transition: width 0.4s; }
.urgency-fill.urg-overdue  { background: var(--po-danger); }
.urgency-fill.urg-critical { background: #f97316; }
.urgency-fill.urg-urgent   { background: var(--po-warning); }
.urgency-fill.urg-soon     { background: var(--po-accent); }
.urgency-fill.urg-ok       { background: var(--po-success); }
.urgency-fill.urg-done     { background: var(--po-muted); }
.urgency-count { font-size: 0.78rem; font-weight: 700; color: var(--po-text); min-width: 16px; text-align: right; }

/* Urgency dot colors for list items */
.urgency-dot.urg-overdue  { background: var(--po-danger); }
.urgency-dot.urg-critical { background: #f97316; }
.urgency-dot.urg-urgent   { background: var(--po-warning); }
.urgency-dot.urg-soon     { background: var(--po-accent); }
.urgency-dot.urg-ok       { background: var(--po-success); }
.urgency-dot.urg-done     { background: var(--po-muted); }

/* Upcoming dot colors */
.upcoming-dot.urg-overdue  { background: var(--po-danger); }
.upcoming-dot.urg-critical { background: #f97316; }
.upcoming-dot.urg-urgent   { background: var(--po-warning); }
.upcoming-dot.urg-soon     { background: var(--po-accent); }
.upcoming-dot.urg-ok       { background: var(--po-success); }
.upcoming-dot.urg-done     { background: var(--po-muted); }

/* ── Modal ── */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.4);
  backdrop-filter: blur(2px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
}

.modal {
  background: white;
  border-radius: 16px;
  width: 100%;
  max-width: 640px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0,0,0,0.2);
  animation: slideUp 0.2s ease;
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}

.modal-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  padding: 1.5rem;
  border-bottom: 1px solid var(--po-border);
  gap: 1rem;
}

.modal-title {
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--po-blue);
  margin: 0 0 0.5rem;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  color: var(--po-muted);
  padding: 0.2rem;
  flex-shrink: 0;
}
.modal-close:hover { color: var(--po-text); }

.modal-body { padding: 1.5rem; }

.modal-meta {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1rem;
  margin-bottom: 1.25rem;
}

.meta-item { display: flex; flex-direction: column; gap: 0.25rem; }
.meta-label { font-size: 0.7rem; font-weight: 700; color: var(--po-muted); text-transform: uppercase; letter-spacing: 0.05em; }
.meta-val { font-weight: 600; color: var(--po-text); }
.deadline-val { color: var(--po-danger); }

.modal-desc {
  font-size: 0.88rem;
  color: var(--po-muted);
  line-height: 1.6;
  margin-bottom: 1.25rem;
}

.modal-section { margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid var(--po-border); }
.modal-section-title { font-size: 0.82rem; font-weight: 700; color: var(--po-blue); text-transform: uppercase; letter-spacing: 0.06em; margin: 0 0 0.75rem; }

.dev-list { display: flex; flex-direction: column; gap: 0.6rem; }
.dev-row { display: flex; align-items: center; gap: 0.75rem; }
.large-avatar { width: 36px; height: 36px; font-size: 0.8rem; }
.dev-name { font-weight: 600; font-size: 0.88rem; margin: 0; }
.dev-role { font-size: 0.75rem; color: var(--po-muted); margin: 0; }

.task-list { display: flex; flex-direction: column; gap: 0.5rem; }
.task-row { display: flex; align-items: center; gap: 0.65rem; padding: 0.4rem 0; border-bottom: 1px solid var(--po-border); }
.task-row:last-child { border-bottom: none; }
.task-status-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.task-completed { background: var(--po-success); }
.task-in_progress { background: var(--po-accent); }
.task-pending { background: var(--po-warning); }
.task-name { flex: 1; font-size: 0.85rem; }
</style>