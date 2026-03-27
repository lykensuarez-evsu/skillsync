<?php
// SkillSync - Main Landing Page

$project = [
    'name' => 'SkillSync',
    'subtitle' => 'EVSU Student Skills and Internship Matching System',
    'institution' => 'Eastern Visayas State University',
    'sdg' => 'Goal 4 - Quality Education',
    'target' => '4.4 Relevant skills for employment',
    'team' => 'Lyken J. Suarez, Shun Arthur Somoray, Arth Emann C. Ecal',
    'description' => 'SkillSync is a student internship matching system that helps connect academic preparation with available internship opportunities. It is designed to organize student profiles, internship listings, and recommendation results in one platform.',
    'problem' => 'Student records and internship listings are often handled separately, which can slow down endorsement and make it harder to identify suitable opportunities.',
    'solution' => 'The system compares student skills, completed subjects, GPA, and preferred track with internship requirements to generate practical recommendations.'
];

$techStack = [
    ['name' => 'PHP', 'role' => 'Backend logic and server-side data handling'],
    ['name' => 'MySQL', 'role' => 'Storage for students, internships, and application records'],
    ['name' => 'JavaScript', 'role' => 'Frontend interactivity and AJAX-based updates'],
    ['name' => 'REST API', 'role' => 'Integration layer for exchanging records between modules']
];

$students = [
    [
        'id' => '2021-001',
        'name' => 'Alyssa Mae Tan',
        'program' => 'BS Information Technology',
        'year_level' => 4,
        'gpa' => 1.68,
        'preferred_track' => 'Web Development',
        'skills' => ['PHP', 'JavaScript', 'Bootstrap', 'REST API', 'MySQL'],
        'completed_subjects' => ['Web Systems', 'Database Systems', 'Systems Analysis', 'Information Assurance']
    ],
    [
        'id' => '2021-014',
        'name' => 'John Carlo Reyes',
        'program' => 'BS Information Technology',
        'year_level' => 4,
        'gpa' => 1.95,
        'preferred_track' => 'Data and QA',
        'skills' => ['SQL', 'Manual Testing', 'Python', 'Documentation'],
        'completed_subjects' => ['Database Systems', 'Software Engineering', 'Human Computer Interaction']
    ],
    [
        'id' => '2021-026',
        'name' => 'Mikaela Joy Dela Cruz',
        'program' => 'BS Information Technology',
        'year_level' => 3,
        'gpa' => 1.75,
        'preferred_track' => 'Systems and Support',
        'skills' => ['Networking', 'Technical Support', 'Linux', 'Documentation'],
        'completed_subjects' => ['Networking 1', 'Operating Systems', 'Information Assurance']
    ]
];

$internships = [
    [
        'id' => 'INT-100',
        'title' => 'Junior Web Development Intern',
        'company' => 'Tacloban Digital Solutions',
        'department' => 'BSIT Internship Office',
        'location' => 'Tacloban City',
        'mode' => 'Hybrid',
        'track' => 'Web Development',
        'min_gpa' => 2.0,
        'required_skills' => ['PHP', 'JavaScript', 'MySQL'],
        'preferred_subjects' => ['Web Systems', 'Database Systems', 'Systems Analysis']
    ],
    [
        'id' => 'INT-101',
        'title' => 'QA and Documentation Intern',
        'company' => 'Eastern Tech Labs',
        'department' => 'ICT Office',
        'location' => 'Ormoc City',
        'mode' => 'On-site',
        'track' => 'Data and QA',
        'min_gpa' => 2.25,
        'required_skills' => ['Manual Testing', 'Documentation', 'SQL'],
        'preferred_subjects' => ['Software Engineering', 'Human Computer Interaction', 'Database Systems']
    ],
    [
        'id' => 'INT-102',
        'title' => 'IT Support Intern',
        'company' => 'Visayas CampusNet',
        'department' => 'External Campuses Coordination',
        'location' => 'Baybay City',
        'mode' => 'On-site',
        'track' => 'Systems and Support',
        'min_gpa' => 2.5,
        'required_skills' => ['Networking', 'Technical Support', 'Linux'],
        'preferred_subjects' => ['Networking 1', 'Operating Systems', 'Information Assurance']
    ],
    [
        'id' => 'INT-103',
        'title' => 'API Integration Intern',
        'company' => 'Leyte Software House',
        'department' => 'University Systems Team',
        'location' => 'Tacloban City',
        'mode' => 'Remote',
        'track' => 'Web Development',
        'min_gpa' => 1.9,
        'required_skills' => ['REST API', 'PHP', 'JavaScript'],
        'preferred_subjects' => ['Web Systems', 'Information Assurance', 'Database Systems']
    ]
];


// Include header
require_once 'includes/header.php';
?>

        <section class="hero">

            <div class="container hero-grid">
                <div class="panel hero-copy">
                    <div class="eyebrow">Capstone Project Demo</div>
                    <h1>Matching student skills with internship opportunities in one system.</h1>
                    <p class="lead"><?php echo htmlspecialchars($project['description']); ?></p>
                    <div class="hero-actions">
                        <a class="button button-primary" href="#demo">Open Live Demo</a>
                        <a class="button button-secondary" href="#overview">Read Project Overview</a>
                    </div>
                    <div class="stat-grid">
                        <div class="stat-card">
                            <span class="stat-value"><?php echo count($students); ?></span>
                            <span class="muted">Sample student profiles</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-value"><?php echo count($internships); ?></span>
                            <span class="muted">Internship records</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-value">4-layer</span>
                            <span class="muted">PHP, MySQL, JS, REST API</span>
                        </div>
                    </div>
                </div>
                <aside class="panel hero-side">
                    <div class="summary-card">
                        <h3>Project Details</h3>
                        <div class="summary-list">
                            <div class="summary-item"><span class="summary-label">Institution</span><strong><?php echo htmlspecialchars($project['institution']); ?></strong></div>
                            <div class="summary-item"><span class="summary-label">SDG</span><strong><?php echo htmlspecialchars($project['sdg']); ?></strong></div>
                            <div class="summary-item"><span class="summary-label">Target</span><strong><?php echo htmlspecialchars($project['target']); ?></strong></div>
                            <div class="summary-item"><span class="summary-label">Team</span><strong><?php echo htmlspecialchars($project['team']); ?></strong></div>
                        </div>
                    </div>
                    <div class="summary-card">
                        <h3>Why this matters</h3>
                        <p class="muted" style="margin-bottom:10px;"><?php echo htmlspecialchars($project['problem']); ?></p>
                        <p class="muted" style="margin-bottom:0;"><?php echo htmlspecialchars($project['solution']); ?></p>
                    </div>
                </aside>
            </div>
        </section>

        <section id="overview" class="section">
            <div class="container">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">Project Overview</h2>
                        <p class="section-subtitle">The page is designed as a clean academic project landing page. It presents the system clearly, shows the tech stack, and lets panel members try the student and admin modules in one place.</p>
                    </div>
                </div>
                <div class="grid-2">
                    <article class="card soft">
                        <span class="label">Problem Statement</span>
                        <h3>Separated records create slower coordination.</h3>
                        <p class="muted"><?php echo htmlspecialchars($project['problem']); ?></p>
                    </article>
                    <article class="card soft">
                        <span class="label">Proposed Solution</span>
                        <h3>One platform for profiling, matching, and management.</h3>
                        <p class="muted"><?php echo htmlspecialchars($project['solution']); ?></p>
                    </article>
                </div>
            </div>
        </section>

        <section id="features" class="section">
            <div class="container">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">Key Features</h2>
                        <p class="section-subtitle">The interface keeps the presentation practical. It avoids exaggerated effects and focuses on what the system can do.</p>
                    </div>
                </div>
                <div class="grid-3">
                    <article class="card">
                        <span class="label">Student Module</span>
                        <h3>Profile-based matching</h3>
                        <p class="muted">Students can be evaluated using GPA, completed subjects, preferred track, and declared skills.</p>
                    </article>
                    <article class="card">
                        <span class="label">Recommendation Engine</span>
                        <h3>Ranked internship results</h3>
                        <p class="muted">Internship suggestions are shown with a score so the panel can see why a recommendation appears first.</p>
                    </article>
                    <article class="card">
                        <span class="label">Admin Module</span>
                        <h3>Manage demo records</h3>
                        <p class="muted">Admins can add, edit, delete, and reset sample records for students and internship opportunities.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="card">
                    <div class="section-header" style="margin-bottom: 14px;">
                        <div>
                            <h2 class="section-title" style="font-size:1.45rem;">Technology Stack</h2>
                            <p class="section-subtitle">This version is written as a PHP page while still showing how the full stack fits together in the project architecture.</p>
                        </div>
                    </div>
                    <div class="grid-4">
                        <?php foreach ($techStack as $item): ?>
                            <div class="mini-card">
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                <p class="muted" style="margin-bottom:0;"><?php echo htmlspecialchars($item['role']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <section id="workflow" class="section">
            <div class="container">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">How the Matching Works</h2>
                        <p class="section-subtitle">The process is simple enough to explain during a defense, while still showing meaningful matching logic.</p>
                    </div>
                </div>
                <div class="steps">
                    <article class="step-card">
                        <div class="step-number">1</div>
                        <h3>Collect student data</h3>
                        <p class="muted">The system reads the student profile, including skills, completed subjects, GPA, and preferred track.</p>
                    </article>
                    <article class="step-card">
                        <div class="step-number">2</div>
                        <h3>Compare with internship requirements</h3>
                        <p class="muted">Each internship record is evaluated based on required skills, preferred subjects, GPA threshold, and track alignment.</p>
                    </article>
                    <article class="step-card">
                        <div class="step-number">3</div>
                        <h3>Show ranked results</h3>
                        <p class="muted">Matching opportunities are sorted by score so users can immediately see the strongest internship recommendations.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="demo" class="section">
            <div class="container">
                <div class="panel workspace">
                    <div class="section-header">
                        <div>
                            <h2 class="section-title">System Demo</h2>
                            <p class="section-subtitle">Try the student side and the admin side below. The page uses PHP for the server-rendered structure and JavaScript for interactive behavior.</p>
                        </div>
                    </div>

                    <div class="tab-row">
                        <button class="tab-button active" type="button" data-tab="student">Student View</button>
                        <button class="tab-button" type="button" data-tab="admin">Admin View</button>
                    </div>

                    <section class="tab-section" id="studentTab">
                        <div class="toolbar">
                            <div>
                                <h3 style="margin-bottom:4px;">Student Matching Workspace</h3>
                                <p class="muted" style="margin-bottom:0;">Select a profile to view student information and generated recommendations.</p>
                            </div>
                            <div class="toolbar-group">
                                <div style="min-width:280px;">
                                    <label for="studentSelect">Select Student</label>
                                    <select id="studentSelect"></select>
                                </div>
                                <button id="refreshButton" class="button-secondary" type="button">Refresh Results</button>
                            </div>
                        </div>

                        <div class="profile-layout">
                            <div class="profile-card">
                                <h3>Student Profile</h3>
                                <div id="studentProfile"></div>
                            </div>
                            <div class="profile-card">
                                <h3>Top Recommendations</h3>
                                <div id="recommendations" class="results-grid"></div>
                            </div>
                        </div>

                        <div class="card" style="margin-top:18px; box-shadow:none;">
                            <h3>Available Internship Listings</h3>
                            <p class="muted">All demo listings are shown below for quick reference during the presentation.</p>
                            <div id="internshipList" class="listing-grid"></div>
                        </div>
                    </section>

                    <section class="tab-section hidden" id="adminTab">
                        <div class="grid-2">
                            <div class="card" style="box-shadow:none;">
                                <h3>Admin Access</h3>
                                <p class="muted">This demo uses a simple local login for presentation purposes only.</p>
                                <div class="admin-status" id="adminStatus">Status: Logged out</div>
                                <div id="adminLoginSection" style="margin-top:16px;">
                                    <div class="form-grid">
                                        <div>
                                            <label for="adminUsername">Username</label>
                                            <input id="adminUsername" type="text" placeholder="Enter admin username">
                                        </div>
                                        <div>
                                            <label for="adminPassword">Password</label>
                                            <input id="adminPassword" type="password" placeholder="Enter admin password">
                                        </div>
                                    </div>
                                    <div class="toolbar-group" style="margin-top:14px;">
                                        <button id="adminLoginButton" type="button">Log In</button>
                                    </div>
                                </div>
                                <div id="adminPanel" class="hidden" style="margin-top:16px;">
                                    <div class="toolbar-group">
                                        <button id="adminLogoutButton" class="button-secondary" type="button">Log Out</button>
                                        <button id="resetDataButton" class="button-secondary" type="button">Reset Demo Data</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card soft" style="box-shadow:none;">
                                <span class="label">Demo Note</span>
                                <h3>Recommended backend setup</h3>
                                <p class="muted">For the actual deployed version, the login, student records, and internship records should be validated and stored using PHP with MySQL. AJAX requests can call REST endpoints for create, update, read, and delete operations.</p>
                            </div>
                        </div>

                        <div id="adminContent" class="hidden" style="margin-top:18px; display:block;">
                            <div class="grid-2">
                                <div class="card" style="box-shadow:none;">
                                    <h3>Student Editor</h3>
                                    <p class="muted">Use the form below to add a new student or update an existing one.</p>
                                    <div class="form-grid">
                                        <div><label for="newStudentId">Student ID</label><input id="newStudentId" type="text" placeholder="2021-099"></div>
                                        <div><label for="newStudentName">Name</label><input id="newStudentName" type="text" placeholder="Student name"></div>
                                        <div><label for="newStudentProgram">Program</label><input id="newStudentProgram" type="text" placeholder="BS Information Technology"></div>
                                        <div><label for="newStudentYear">Year Level</label><input id="newStudentYear" type="number" min="1" max="5" placeholder="4"></div>
                                        <div><label for="newStudentGpa">GPA</label><input id="newStudentGpa" type="number" step="0.01" placeholder="1.80"></div>
                                        <div><label for="newStudentTrack">Preferred Track</label><input id="newStudentTrack" type="text" placeholder="Web Development"></div>
                                        <div class="full-span"><label for="newStudentSkills">Skills</label><input id="newStudentSkills" type="text" placeholder="PHP, JavaScript, MySQL"></div>
                                        <div class="full-span"><label for="newStudentSubjects">Completed Subjects</label><input id="newStudentSubjects" type="text" placeholder="Web Systems, Database Systems"></div>
                                    </div>
                                    <div class="toolbar-group" style="margin-top:14px;">
                                        <button id="addStudentButton" type="button">Add Student</button>
                                        <button id="cancelStudentEditButton" class="button-secondary hidden" type="button">Cancel Edit</button>
                                    </div>
                                </div>

                                <div class="card" style="box-shadow:none;">
                                    <h3>Internship Editor</h3>
                                    <p class="muted">Use the form below to add a new internship or update an existing one.</p>
                                    <div class="form-grid">
                                        <div><label for="newInternshipId">Internship ID</label><input id="newInternshipId" type="text" placeholder="INT-200"></div>
                                        <div><label for="newInternshipTitle">Title</label><input id="newInternshipTitle" type="text" placeholder="Frontend Intern"></div>
                                        <div><label for="newInternshipCompany">Company</label><input id="newInternshipCompany" type="text" placeholder="Company name"></div>
                                        <div><label for="newInternshipDepartment">Department</label><input id="newInternshipDepartment" type="text" placeholder="BSIT Internship Office"></div>
                                        <div><label for="newInternshipLocation">Location</label><input id="newInternshipLocation" type="text" placeholder="Tacloban City"></div>
                                        <div><label for="newInternshipMode">Mode</label><input id="newInternshipMode" type="text" placeholder="Hybrid"></div>
                                        <div><label for="newInternshipTrack">Track</label><input id="newInternshipTrack" type="text" placeholder="Web Development"></div>
                                        <div><label for="newInternshipGpa">Minimum GPA</label><input id="newInternshipGpa" type="number" step="0.01" placeholder="2.00"></div>
                                        <div class="full-span"><label for="newInternshipSkills">Required Skills</label><input id="newInternshipSkills" type="text" placeholder="HTML, CSS, JavaScript"></div>
                                        <div class="full-span"><label for="newInternshipSubjects">Preferred Subjects</label><input id="newInternshipSubjects" type="text" placeholder="Web Systems, HCI"></div>
                                    </div>
                                    <div class="toolbar-group" style="margin-top:14px;">
                                        <button id="addInternshipButton" type="button">Add Internship</button>
                                        <button id="cancelInternshipEditButton" class="button-secondary hidden" type="button">Cancel Edit</button>
                                    </div>
                                </div>
                            </div>

                            <div class="grid-2" style="margin-top:18px;">
                                <div class="card" style="box-shadow:none;">
                                    <h3>Manage Students</h3>
                                    <div id="studentAdminList"></div>
                                </div>
                                <div class="card" style="box-shadow:none;">
                                    <h3>Manage Internships</h3>
                                    <div id="internshipAdminList"></div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </main>


<?php
// Include footer
require_once 'includes/footer.php';
?>
