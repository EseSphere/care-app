<?php include_once 'header.php'; ?>

<div class="main-wrapper container mt-3">
    <div class="row gutters-sm">
        <!-- Client Profile Horizontal Layout -->
        <?php require_once 'client-profile-extension.php'; ?>

        <!-- My Likes & Dislikes Card -->
        <div class="col-md-12 mb-3">
            <div class="card p-3 assessment-card">
                <h5>My Likes & Dislikes</h5>
                <hr>
                <div id="mladContent">Loading...</div>
            </div>
        </div>

        <!-- Navigation Cards to Other Assessments -->
        <div class="col-md-12 mt-3">
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Other Assessments</h5>
                </div>
                <div id="assessmentCards">
                    <!-- Dynamic assessment cards inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('care_app');
            request.onsuccess = e => resolve(e.target.result);
            request.onerror = e => reject(e.target.error);
        });
    }

    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    function calculateAge(dob) {
        if (!dob) return '--';
        const birthDate = new Date(dob);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        const dayDiff = today.getDate() - birthDate.getDate();
        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) age--;
        return age;
    }

    function createInitialsCircle(fullName, fontSize = 2, diameter = 100) {
        if (!fullName) fullName = '--';
        const names = fullName.split(' ');
        const initials = ((names[0]?.charAt(0) || '') + (names[1]?.charAt(0) || '')).toUpperCase();
        const colors = ["#6c757d", "#0d6efd", "#198754", "#dc3545", "#ffc107", "#6f42c1", "#fd7e14"];
        const charCodeSum = (initials.charCodeAt(0) || 0) + (initials.charCodeAt(1) || 0);
        const bgColor = colors[charCodeSum % colors.length];

        const div = document.createElement('div');
        div.textContent = initials;
        div.style.width = `${diameter}px`;
        div.style.height = `${diameter}px`;
        div.style.borderRadius = '50%';
        div.style.display = 'flex';
        div.style.alignItems = 'center';
        div.style.justifyContent = 'center';
        div.style.fontSize = `${fontSize}rem`;
        div.style.fontWeight = 'bold';
        div.style.color = 'white';
        div.style.backgroundColor = bgColor;
        div.style.marginBottom = '5px';
        div.id = 'clientInitials';
        return div;
    }

    async function loadMLAD() {
        const uryyToeSS4 = getQueryParam('uryyToeSS4');
        if (!uryyToeSS4) {
            document.getElementById('mladContent').textContent = 'No client selected.';
            return;
        }

        const db = await openDB();
        const tx = db.transaction('tbl_general_client_form', 'readonly');
        const store = tx.objectStore('tbl_general_client_form');
        const req = store.getAll();

        req.onsuccess = e => {
            const client = e.target.result.find(c => c.uryyToeSS4 === uryyToeSS4);
            if (!client) {
                document.getElementById('mladContent').textContent = 'Client not found.';
                return;
            }

            // Profile header
            const initialsDiv = document.getElementById('clientInitials');
            const initialsCircle = createInitialsCircle(`${client.client_first_name} ${client.client_last_name}`);
            initialsDiv.replaceWith(initialsCircle);
            document.getElementById('clientName').textContent = `${client.client_first_name} ${client.client_last_name}`;
            document.getElementById('clientAge').textContent = `Age: ${calculateAge(client.client_date_of_birth)}`;
            document.getElementById('dnacprBtn').href = `health.php?uryyToeSS4=${client.uryyToeSS4}`;
            document.getElementById('allergiesBtn').href = `emergency.php?uryyToeSS4=${client.uryyToeSS4}`;

            // My Likes & Dislikes
            const content = client.my_likes_and_dislikes || 'No data available.';
            const formattedContent = content.split(/\n\s*\n/).map(p => `<p>${p.trim().replace(/\n/g,'<br>')}</p>`).join('');
            document.getElementById('mladContent').innerHTML = formattedContent;

            // Other assessments cards
            const assessments = [{
                    title: 'What is Important to Me',
                    link: `wiitm.php?uryyToeSS4=${client.uryyToeSS4}`,
                    icon: 'bi-heart'
                },
                {
                    title: 'My Current Condition',
                    link: `mcc.php?uryyToeSS4=${client.uryyToeSS4}`,
                    icon: 'bi-activity'
                },
                {
                    title: 'My Medical History',
                    link: `mmh.php?uryyToeSS4=${client.uryyToeSS4}`,
                    icon: 'bi-journal-medical'
                },
                {
                    title: 'My Physical Health',
                    link: `mph.php?uryyToeSS4=${client.uryyToeSS4}`,
                    icon: 'bi-heart-pulse'
                },
                {
                    title: 'My Mental Health',
                    link: `mmh.php?uryyToeSS4=${client.uryyToeSS4}`,
                    icon: 'bi-brain'
                },
                {
                    title: 'How I Communicate',
                    link: `hic.php?uryyToeSS4=${client.uryyToeSS4}`,
                    icon: 'bi-chat-left-text'
                },
                {
                    title: 'Assistive Equipment',
                    link: `aei.php?uryyToeSS4=${client.uryyToeSS4}`,
                    icon: 'bi-tools'
                }
            ];

            const assessmentContainer = document.getElementById('assessmentCards');
            assessmentContainer.innerHTML = '';
            assessments.forEach(a => {
                const card = document.createElement('div');
                card.className = 'card mb-2 p-3 assessment-card';
                card.innerHTML = `<a href="${a.link}"><i class="bi ${a.icon} me-2"></i>${a.title}</a>`;
                assessmentContainer.appendChild(card);
            });
        };

        req.onerror = e => console.error(e.target.error);
    }

    loadMLAD();
</script>

<?php include_once 'footer.php'; ?>