// SkillSync - Main Application Script

// API Base URL
const API_BASE = 'api';

document.addEventListener('DOMContentLoaded', function() {
    console.log('SkillSync App Loaded');
    
    // Initialize event listeners and data
    initializeApp();
});

// Global data (will be populated from API)
let defaultStudents = [];
let defaultInternships = [];

const ADMIN_USERNAME = 'admin';
const ADMIN_PASSWORD = 'skillsync123';

let students = [];
let internships = [];
let isAdminLoggedIn = false;
let editingStudentIndex = null;
let editingInternshipIndex = null;

// DOM Elements
let tabButtons, studentTab, adminTab, studentSelect, studentProfile, recommendations;
let internshipList, refreshButton, adminStatus, adminLoginSection, adminPanel, adminContent;
let adminUsername, adminPassword, adminLoginButton, adminLogoutButton, resetDataButton;
let addStudentButton, addInternshipButton, cancelStudentEditButton, cancelInternshipEditButton;
let studentAdminList, internshipAdminList;

function initializeApp() {
    cacheElements();
    attachEventListeners();
    loadData();
    updateAdminView();
    setActiveTab('student');
    renderAll();
}

function cacheElements() {
    tabButtons = document.querySelectorAll('.tab-button');
    studentTab = document.getElementById('studentTab');
    adminTab = document.getElementById('adminTab');
    studentSelect = document.getElementById('studentSelect');
    studentProfile = document.getElementById('studentProfile');
    recommendations = document.getElementById('recommendations');
    internshipList = document.getElementById('internshipList');
    refreshButton = document.getElementById('refreshButton');
    adminStatus = document.getElementById('adminStatus');
    adminLoginSection = document.getElementById('adminLoginSection');
    adminPanel = document.getElementById('adminPanel');
    adminContent = document.getElementById('adminContent');
    adminUsername = document.getElementById('adminUsername');
    adminPassword = document.getElementById('adminPassword');
    adminLoginButton = document.getElementById('adminLoginButton');
    adminLogoutButton = document.getElementById('adminLogoutButton');
    resetDataButton = document.getElementById('resetDataButton');
    addStudentButton = document.getElementById('addStudentButton');
    addInternshipButton = document.getElementById('addInternshipButton');
    cancelStudentEditButton = document.getElementById('cancelStudentEditButton');
    cancelInternshipEditButton = document.getElementById('cancelInternshipEditButton');
    studentAdminList = document.getElementById('studentAdminList');
    internshipAdminList = document.getElementById('internshipAdminList');
}

function attachEventListeners() {
    tabButtons.forEach((button) => {
        button.addEventListener('click', () => setActiveTab(button.dataset.tab));
    });

    studentSelect.addEventListener('change', renderAll);
    refreshButton.addEventListener('click', renderAll);
    adminLoginButton.addEventListener('click', handleAdminLogin);
    adminLogoutButton.addEventListener('click', handleAdminLogout);
    resetDataButton.addEventListener('click', handleResetData);
    cancelStudentEditButton.addEventListener('click', finishStudentEdit);
    cancelInternshipEditButton.addEventListener('click', finishInternshipEdit);
    addStudentButton.addEventListener('click', handleAddStudent);
    addInternshipButton.addEventListener('click', handleAddInternship);
    
    studentAdminList.addEventListener('click', handleStudentAdminActions);
    internshipAdminList.addEventListener('click', handleInternshipAdminActions);
}

function cloneRecords(records) {
    return records.map((record) => ({
        ...record,
        skills: record.skills ? [...record.skills] : undefined,
        completed_subjects: record.completed_subjects ? [...record.completed_subjects] : undefined,
        required_skills: record.required_skills ? [...record.required_skills] : undefined,
        preferred_subjects: record.preferred_subjects ? [...record.preferred_subjects] : undefined
    }));
}

function loadData() {
    // Load students from API
    fetch(`${API_BASE}/students.php?action=all`)
        .then(response => response.json())
        .then(data => {
            students = data || [];
            populateStudents();
            renderAll();
        })
        .catch(error => {
            console.error('Error loading students:', error);
            students = [];
        });
    
    // Load internships from API
    fetch(`${API_BASE}/internships.php?action=all`)
        .then(response => response.json())
        .then(data => {
            internships = data || [];
            renderAll();
        })
        .catch(error => {
            console.error('Error loading internships:', error);
            internships = [];
        });
}

function saveData() {
    // Data is saved automatically on each operation (POST/PUT/DELETE)
    console.log('Data saved to database');
}

function parseList(value) {
    return value.split(',').map((item) => item.trim()).filter(Boolean);
}

function setActiveTab(tabName) {
    const isStudent = tabName === 'student';
    studentTab.classList.toggle('hidden', !isStudent);
    adminTab.classList.toggle('hidden', isStudent);
    tabButtons.forEach((button) => {
        button.classList.toggle('active', button.dataset.tab === tabName);
    });
}

function updateAdminView() {
    adminStatus.textContent = isAdminLoggedIn ? 'Status: Logged in as admin' : 'Status: Logged out';
    adminLoginSection.classList.toggle('hidden', isAdminLoggedIn);
    adminPanel.classList.toggle('hidden', !isAdminLoggedIn);
    adminContent.classList.toggle('hidden', !isAdminLoggedIn);
}

function getSelectedStudent() {
    const index = Number(studentSelect.value);
    return Number.isFinite(index) ? students[index] : undefined;
}

function getMatches(student) {
    return internships
        .map((internship) => {
            const matchedSkills = internship.required_skills.filter((skill) => student.skills.includes(skill));
            const matchedSubjects = internship.preferred_subjects.filter((subject) => student.completed_subjects.includes(subject));

            let score = 0;
            score += internship.required_skills.length ? (matchedSkills.length / internship.required_skills.length) * 50 : 0;
            score += internship.preferred_subjects.length ? (matchedSubjects.length / internship.preferred_subjects.length) * 25 : 0;

            if (student.gpa <= internship.min_gpa) {
                score += 25;
            } else if (student.gpa <= internship.min_gpa + 0.25) {
                score += 15;
            }

            if (student.preferred_track === internship.track) {
                score += 5;
            }

            return {
                ...internship,
                matchedSkills,
                matchedSubjects,
                score: Math.min(100, Math.round(score))
            };
        })
        .sort((a, b) => b.score - a.score);
}

function renderTags(items) {
    if (!items.length) {
        return '<span class="muted">None listed</span>';
    }
    return `<div class="tag-list">${items.map((item) => `<span class="tag">${item}</span>`).join('')}</div>`;
}

function renderStudentProfile(student) {
    if (!student) {
        studentProfile.innerHTML = '<p class="muted">No student selected.</p>';
        return;
    }

    studentProfile.innerHTML = `
        <div class="info-list">
            <div class="info-row"><span class="muted">Name</span><strong>${student.name}</strong></div>
            <div class="info-row"><span class="muted">Student ID</span><strong>${student.id}</strong></div>
            <div class="info-row"><span class="muted">Program</span><strong>${student.program}</strong></div>
            <div class="info-row"><span class="muted">Year Level</span><strong>${student.year_level}</strong></div>
            <div class="info-row"><span class="muted">GPA</span><strong>${student.gpa}</strong></div>
            <div class="info-row"><span class="muted">Preferred Track</span><strong>${student.preferred_track}</strong></div>
        </div>
        <h4>Skills</h4>
        ${renderTags(student.skills)}
        <h4 style="margin-top:16px;">Completed Subjects</h4>
        ${renderTags(student.completed_subjects)}
    `;
}

function renderRecommendations(student) {
    recommendations.innerHTML = '';

    if (!student) {
        recommendations.innerHTML = '<p class="muted">No recommendations available.</p>';
        return;
    }

    const matches = getMatches(student);
    recommendations.innerHTML = matches.map((item) => `
        <article class="result-card">
            <div class="result-top">
                <div>
                    <h4>${item.title}</h4>
                    <p class="muted" style="margin-bottom:0;">${item.company}</p>
                </div>
                <span class="score-chip">${item.score}% match</span>
            </div>
            <div class="progress"><span style="width:${item.score}%;"></span></div>
            <div class="meta-row">
                <span class="meta-pill">${item.track}</span>
                <span class="meta-pill">${item.mode}</span>
                <span class="meta-pill">${item.location}</span>
            </div>
            <p class="muted"><strong>Matched skills:</strong> ${item.matchedSkills.length ? item.matchedSkills.join(', ') : 'No direct skill match'}</p>
            <p class="muted" style="margin-bottom:0;"><strong>Matched subjects:</strong> ${item.matchedSubjects.length ? item.matchedSubjects.join(', ') : 'No direct subject match'}</p>
        </article>
    `).join('');
}

function renderInternships() {
    internshipList.innerHTML = internships.map((item) => `
        <article class="listing-card">
            <div class="listing-top">
                <div>
                    <h4>${item.title}</h4>
                    <p class="muted" style="margin-bottom:0;">${item.company}</p>
                </div>
                <span class="score-chip">${item.id}</span>
            </div>
            <div class="meta-row">
                <span class="meta-pill">${item.track}</span>
                <span class="meta-pill">${item.mode}</span>
                <span class="meta-pill">Min GPA ${item.min_gpa}</span>
            </div>
            <p class="muted"><strong>Location:</strong> ${item.location}</p>
            <p class="muted"><strong>Required Skills:</strong> ${item.required_skills.join(', ')}</p>
            <p class="muted" style="margin-bottom:0;"><strong>Preferred Subjects:</strong> ${item.preferred_subjects.join(', ')}</p>
        </article>
    `).join('');
}

function populateStudents() {
    const currentValue = studentSelect.value;
    studentSelect.innerHTML = students.map((student, index) => `
        <option value="${index}">${student.name} - ${student.preferred_track}</option>
    `).join('');

    if (!students.length) {
        studentSelect.innerHTML = '<option value="">No student records</option>';
        return;
    }

    if (currentValue && students[Number(currentValue)]) {
        studentSelect.value = currentValue;
    } else {
        studentSelect.value = '0';
    }
}

function renderStudentAdminTable() {
    if (!students.length) {
        studentAdminList.innerHTML = '<p class="muted">No student records available.</p>';
        return;
    }

    studentAdminList.innerHTML = `
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Track</th>
                        <th>GPA</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${students.map((student, index) => `
                        <tr>
                            <td>${student.id}</td>
                            <td>${student.name}</td>
                            <td>${student.preferred_track}</td>
                            <td>${student.gpa}</td>
                            <td>
                                <div class="row-actions">
                                    <button type="button" class="action-button edit-student" data-index="${index}">Edit</button>
                                    <button type="button" class="action-button delete-student" data-index="${index}">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}

function renderInternshipAdminTable() {
    if (!internships.length) {
        internshipAdminList.innerHTML = '<p class="muted">No internship records available.</p>';
        return;
    }

    internshipAdminList.innerHTML = `
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Track</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${internships.map((internship, index) => `
                        <tr>
                            <td>${internship.id}</td>
                            <td>${internship.title}</td>
                            <td>${internship.company}</td>
                            <td>${internship.track}</td>
                            <td>
                                <div class="row-actions">
                                    <button type="button" class="action-button edit-internship" data-index="${index}">Edit</button>
                                    <button type="button" class="action-button delete-internship" data-index="${index}">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;
}

function renderAll() {
    const selectedStudent = getSelectedStudent();
    renderStudentProfile(selectedStudent);
    renderRecommendations(selectedStudent);
    renderInternships();
    renderStudentAdminTable();
    renderInternshipAdminTable();
}

function clearStudentForm() {
    document.getElementById('newStudentId').value = '';
    document.getElementById('newStudentName').value = '';
    document.getElementById('newStudentProgram').value = '';
    document.getElementById('newStudentYear').value = '';
    document.getElementById('newStudentGpa').value = '';
    document.getElementById('newStudentTrack').value = '';
    document.getElementById('newStudentSkills').value = '';
    document.getElementById('newStudentSubjects').value = '';
}

function clearInternshipForm() {
    document.getElementById('newInternshipId').value = '';
    document.getElementById('newInternshipTitle').value = '';
    document.getElementById('newInternshipCompany').value = '';
    document.getElementById('newInternshipDepartment').value = '';
    document.getElementById('newInternshipLocation').value = '';
    document.getElementById('newInternshipMode').value = '';
    document.getElementById('newInternshipTrack').value = '';
    document.getElementById('newInternshipGpa').value = '';
    document.getElementById('newInternshipSkills').value = '';
    document.getElementById('newInternshipSubjects').value = '';
}

function finishStudentEdit() {
    editingStudentIndex = null;
    addStudentButton.textContent = 'Add Student';
    cancelStudentEditButton.classList.add('hidden');
    clearStudentForm();
}

function finishInternshipEdit() {
    editingInternshipIndex = null;
    addInternshipButton.textContent = 'Add Internship';
    cancelInternshipEditButton.classList.add('hidden');
    clearInternshipForm();
}

function startStudentEdit(index) {
    const student = students[index];
    if (!student) return;

    editingStudentIndex = index;
    addStudentButton.textContent = 'Save Student';
    cancelStudentEditButton.classList.remove('hidden');

    document.getElementById('newStudentId').value = student.id;
    document.getElementById('newStudentName').value = student.name;
    document.getElementById('newStudentProgram').value = student.program;
    document.getElementById('newStudentYear').value = student.year_level;
    document.getElementById('newStudentGpa').value = student.gpa;
    document.getElementById('newStudentTrack').value = student.preferred_track;
    document.getElementById('newStudentSkills').value = student.skills.join(', ');
    document.getElementById('newStudentSubjects').value = student.completed_subjects.join(', ');
}

function startInternshipEdit(index) {
    const internship = internships[index];
    if (!internship) return;

    editingInternshipIndex = index;
    addInternshipButton.textContent = 'Save Internship';
    cancelInternshipEditButton.classList.remove('hidden');

    document.getElementById('newInternshipId').value = internship.id;
    document.getElementById('newInternshipTitle').value = internship.title;
    document.getElementById('newInternshipCompany').value = internship.company;
    document.getElementById('newInternshipDepartment').value = internship.department;
    document.getElementById('newInternshipLocation').value = internship.location;
    document.getElementById('newInternshipMode').value = internship.mode;
    document.getElementById('newInternshipTrack').value = internship.track;
    document.getElementById('newInternshipGpa').value = internship.min_gpa;
    document.getElementById('newInternshipSkills').value = internship.required_skills.join(', ');
    document.getElementById('newInternshipSubjects').value = internship.preferred_subjects.join(', ');
}

function handleAdminLogin() {
    if (adminUsername.value.trim() === ADMIN_USERNAME && adminPassword.value === ADMIN_PASSWORD) {
        isAdminLoggedIn = true;
        updateAdminView();
        return;
    }
    alert('Invalid admin credentials. Use admin / skillsync123 for the demo.');
}

function handleAdminLogout() {
    isAdminLoggedIn = false;
    updateAdminView();
}

function handleResetData() {
    students = cloneRecords(defaultStudents);
    internships = cloneRecords(defaultInternships);
    saveData();
    populateStudents();
    finishStudentEdit();
    finishInternshipEdit();
    renderAll();
}

function handleAddStudent() {
    const newStudent = {
        student_id: document.getElementById('newStudentId').value.trim(),
        name: document.getElementById('newStudentName').value.trim(),
        program: document.getElementById('newStudentProgram').value.trim(),
        year_level: Number(document.getElementById('newStudentYear').value),
        gpa: Number(document.getElementById('newStudentGpa').value),
        preferred_track: document.getElementById('newStudentTrack').value.trim(),
        skills: parseList(document.getElementById('newStudentSkills').value),
        completed_subjects: parseList(document.getElementById('newStudentSubjects').value)
    };

    if (!newStudent.student_id || !newStudent.name || !newStudent.program || !newStudent.preferred_track) {
        alert('Please complete the required student fields first.');
        return;
    }

    if (editingStudentIndex === null) {
        // Add new student
        fetch(`${API_BASE}/students.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(newStudent)
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error adding student: ' + data.error);
                return;
            }
            loadData();
            finishStudentEdit();
        })
        .catch(error => alert('Error: ' + error));
    } else {
        // Update existing student
        students[editingStudentIndex] = { ...students[editingStudentIndex], ...newStudent };
        fetch(`${API_BASE}/students.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: students[editingStudentIndex].id, ...newStudent })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error updating student: ' + data.error);
                return;
            }
            loadData();
            finishStudentEdit();
        })
        .catch(error => alert('Error: ' + error));
    }
}

function handleAddInternship() {
    const newInternship = {
        internship_id: document.getElementById('newInternshipId').value.trim(),
        title: document.getElementById('newInternshipTitle').value.trim(),
        company: document.getElementById('newInternshipCompany').value.trim(),
        department: document.getElementById('newInternshipDepartment').value.trim(),
        location: document.getElementById('newInternshipLocation').value.trim(),
        mode: document.getElementById('newInternshipMode').value.trim(),
        track: document.getElementById('newInternshipTrack').value.trim(),
        min_gpa: Number(document.getElementById('newInternshipGpa').value),
        required_skills: parseList(document.getElementById('newInternshipSkills').value),
        preferred_subjects: parseList(document.getElementById('newInternshipSubjects').value)
    };

    if (!newInternship.internship_id || !newInternship.title || !newInternship.company || !newInternship.track) {
        alert('Please complete the required internship fields first.');
        return;
    }

    if (editingInternshipIndex === null) {
        // Add new internship
        fetch(`${API_BASE}/internships.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(newInternship)
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error adding internship: ' + data.error);
                return;
            }
            loadData();
            finishInternshipEdit();
        })
        .catch(error => alert('Error: ' + error));
    } else {
        // Update existing internship
        internships[editingInternshipIndex] = { ...internships[editingInternshipIndex], ...newInternship };
        fetch(`${API_BASE}/internships.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: internships[editingInternshipIndex].id, ...newInternship })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error updating internship: ' + data.error);
                return;
            }
            loadData();
            finishInternshipEdit();
        })
        .catch(error => alert('Error: ' + error));
    }
}

function handleStudentAdminActions(event) {
    const target = event.target;
    if (!(target instanceof HTMLElement)) return;

    if (target.classList.contains('edit-student')) {
        startStudentEdit(Number(target.dataset.index));
        return;
    }

    if (target.classList.contains('delete-student')) {
        const index = Number(target.dataset.index);
        const student = students[index];
        if (!student) return;

        if (window.confirm(`Delete student ${student.name}?`)) {
            fetch(`${API_BASE}/students.php?id=${student.id}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error deleting student: ' + data.error);
                    return;
                }
                loadData();
                finishStudentEdit();
            })
            .catch(error => alert('Error: ' + error));
        }
    }
}

function handleInternshipAdminActions(event) {
    const target = event.target;
    if (!(target instanceof HTMLElement)) return;

    if (target.classList.contains('edit-internship')) {
        startInternshipEdit(Number(target.dataset.index));
        return;
    }

    if (target.classList.contains('delete-internship')) {
        const index = Number(target.dataset.index);
        const internship = internships[index];
        if (!internship) return;

        if (window.confirm(`Delete internship ${internship.title}?`)) {
            fetch(`${API_BASE}/internships.php?id=${internship.id}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error deleting internship: ' + data.error);
                    return;
                }
                loadData();
                finishInternshipEdit();
            })
            .catch(error => alert('Error: ' + error));
        }
    }
}

// Function to set default data (called from index.php)
function setDefaultData(studentsData, internshipsData) {
    defaultStudents = studentsData;
    defaultInternships = internshipsData;
    loadData();
}
