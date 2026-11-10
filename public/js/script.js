/* ==========================
   LOAD USER DATA
========================== */

let userData = {
    xp: 0,
    applied: [],
    completed: []
};

let selectedQuest = null;


/* ==========================
   CSRF TOKEN
========================== */

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content;
}


/* ==========================
   SEND REQUEST TO LARAVEL
========================== */

async function sendQuestRequest(url, questId) {
    const token = getCsrfToken();

    if (!token) {
        throw new Error("CSRF token tidak ditemukan.");
    }

    const response = await fetch(url, {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": token
        },
        body: JSON.stringify({
            quest_id: questId
        })
    });

    const data = await response.json();

    if (!response.ok) {
        throw new Error(data.message || "Request gagal.");
    }

    return data;
}


/* ==========================
   LOAD PROGRESS FROM DATABASE
========================== */

async function loadUserProgress() {
    const response = await fetch("/quests/progress", {
        credentials: "same-origin",
        headers: {
            "Accept": "application/json"
        }
    });

    if (!response.ok) {
        throw new Error("Gagal mengambil progress akun.");
    }

    const data = await response.json();

    userData.xp = Number(data.xp) || 0;
    userData.applied = data.applied.map(Number);
    userData.completed = data.completed.map(Number);

    updateProgress();
    displayQuests(quests);
}


/* ==========================
   APPLY QUEST
========================== */

async function applyQuest(id) {
    if (userData.applied.includes(id)) return;

    try {
        await sendQuestRequest("/quests/apply", id);

        await loadUserProgress();

        if (selectedQuest && selectedQuest.id === id) {
            openQuest(id);
        }

    } catch (error) {
        alert(error.message);
    }
}


/* ==========================
   DISPLAY QUESTS
========================== */

const questGrid = document.getElementById("questGrid");

function displayQuests(data) {
    if (!questGrid) return;

    questGrid.innerHTML = "";

    if (data.length === 0) {
        questGrid.innerHTML =
            '<p class="empty-message">No quests found.</p>';
        return;
    }

    data.forEach(quest => {
        const applied = userData.applied.includes(quest.id);
        const completed = userData.completed.includes(quest.id);

        const card = document.createElement("article");
        card.className = "quest-card";

        const image = document.createElement("img");
        image.src = quest.image;
        image.alt = quest.title;
        image.loading = "lazy";

        const imageBox = document.createElement("div");
        imageBox.className = "quest-image";
        imageBox.appendChild(image);

        const content = document.createElement("div");
        content.className = "quest-content";

        const category = document.createElement("span");
        category.className = "quest-category";
        category.textContent = quest.category.toUpperCase();

        const title = document.createElement("h3");
        title.textContent = quest.title;

        const description = document.createElement("p");
        description.textContent =
            quest.description.slice(0, 90) + "...";

        const meta = document.createElement("div");
        meta.className = "quest-meta";

        const location = document.createElement("span");
        location.textContent = "⌖ " + quest.location;

        const reward = document.createElement("span");
        reward.className = "quest-reward";
        reward.textContent = "+" + quest.reward + " XP";

        meta.append(location, reward);

        const actions = document.createElement("div");
        actions.className = "quest-actions";

        const seeMore = document.createElement("button");
        seeMore.className = "see-more";
        seeMore.textContent = "See More";

        seeMore.addEventListener("click", () => {
            openQuest(quest.id);
        });

        const apply = document.createElement("button");
        apply.className = "apply-button";

        apply.textContent = completed
            ? "Completed"
            : applied ? "Applied" : "Apply";

        apply.disabled = applied || completed;

        if (apply.disabled) {
            apply.style.opacity = "0.6";
            apply.style.cursor = "not-allowed";
        }

        apply.addEventListener("click", () => {
            applyQuest(quest.id);
        });

        actions.append(seeMore, apply);

        content.append(
            category,
            title,
            description,
            meta,
            actions
        );

        card.append(imageBox, content);
        questGrid.appendChild(card);
    });
}


/* ==========================
   OPEN QUEST DETAIL
========================== */

const modal = document.getElementById("questModal");

function openQuest(id) {
    selectedQuest = quests.find(quest => quest.id === id);

    if (!selectedQuest || !modal) return;

    const quest = selectedQuest;

    document.getElementById("modalImage").src = quest.image;
    document.getElementById("modalCategory").textContent =
        quest.category.toUpperCase();
    document.getElementById("modalTitle").textContent = quest.title;
    document.getElementById("modalDescription").textContent =
        quest.description;

    document.getElementById("modalDetails").innerHTML = `
        📍 Location: ${quest.location}<br>
        📅 Date: ${quest.date}<br>
        👥 Participants: ${quest.participants}<br>
        ✦ Reward: ${quest.reward} XP
    `;

    const applyButton = document.getElementById("applyButton");
    const completeButton = document.getElementById("completeButton");

    const applied = userData.applied.includes(id);
    const completed = userData.completed.includes(id);

    applyButton.hidden = applied || completed;
    completeButton.hidden = !applied || completed;

    modal.classList.add("show");
}


/* ==========================
   CLOSE MODAL
========================== */

const closeModal = document.getElementById("closeModal");

if (closeModal && modal) {
    closeModal.addEventListener("click", () => {
        modal.classList.remove("show");
    });

    modal.addEventListener("click", event => {
        if (event.target === modal) {
            modal.classList.remove("show");
        }
    });
}


/* ==========================
   MODAL APPLY BUTTON
========================== */

const modalApplyButton = document.getElementById("applyButton");

if (modalApplyButton) {
    modalApplyButton.addEventListener("click", async () => {
        if (!selectedQuest) return;

        await applyQuest(selectedQuest.id);
    });
}


/* ==========================
   COMPLETE QUEST
========================== */

const completeButton = document.getElementById("completeButton");

if (completeButton) {
    completeButton.addEventListener("click", async () => {
        if (!selectedQuest) return;

        const id = selectedQuest.id;

        if (!userData.applied.includes(id)) return;
        if (userData.completed.includes(id)) return;

        completeButton.disabled = true;

        try {
            const data = await sendQuestRequest(
                "/quests/complete",
                id
            );

            await loadUserProgress();

            openQuest(id);

            alert(
                "Quest completed! You earned " +
                data.reward + " XP."
            );

        } catch (error) {
            await loadUserProgress();
            openQuest(id);
            alert(error.message);

        } finally {
            completeButton.disabled = false;
        }
    });
}


/* ==========================
   SEARCH AND FILTER
========================== */

const searchInput = document.getElementById("searchInput");
const categoryFilter = document.getElementById("categoryFilter");

function filterQuests() {
    if (!searchInput || !categoryFilter) return;

    const keyword = searchInput.value.toLowerCase();
    const category = categoryFilter.value;

    const filtered = quests.filter(quest => {
        const matchesSearch =
            quest.title.toLowerCase().includes(keyword) ||
            quest.location.toLowerCase().includes(keyword);

        const matchesCategory =
            category === "All" ||
            quest.category === category;

        return matchesSearch && matchesCategory;
    });

    displayQuests(filtered);
}

if (searchInput) {
    searchInput.addEventListener("input", filterQuests);
}

if (categoryFilter) {
    categoryFilter.addEventListener("change", filterQuests);
}


/* ==========================
   UPDATE PROGRESS AND ORB
========================== */

function updateProgress() {
    const xp = userData.xp || 0;

    const level = Math.floor(xp / 500) + 1;
    const currentXP = xp % 500;

    const pointsElement = document.getElementById("points");
    if (pointsElement) pointsElement.textContent = xp;

    const totalXP = document.getElementById("totalXP");
    if (totalXP) totalXP.textContent = xp;

    const totalApplied = document.getElementById("totalApplied");
    if (totalApplied) {
        totalApplied.textContent = userData.applied.length;
    }

    const orbLevel = document.getElementById("orbLevel");
    if (orbLevel) orbLevel.textContent = level;

    const orbLevelText = document.getElementById("orbLevelText");
    if (orbLevelText) orbLevelText.textContent = level;

    let orbName = "Seed Orb";

    if (level >= 10) {
        orbName = "Radiant Orb";
    } else if (level >= 5) {
        orbName = "Bloom Orb";
    }

    const orbNameElement = document.getElementById("orbName");
    if (orbNameElement) orbNameElement.textContent = orbName;

    const xpProgress = document.getElementById("xpProgress");
    if (xpProgress) {
        xpProgress.style.width = (currentXP / 500 * 100) + "%";
    }

    const xpText = document.getElementById("xpText");
    if (xpText) {
        xpText.textContent = currentXP + " / 500 XP";
    }
}


/* ==========================
   HERO IMAGE SLIDER
========================== */

const heroImages = [
    "https://images.unsplash.com/photo-1472396961693-142e6e269027?w=1800",
    "https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?w=1800",
    "https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1800"
];

let currentSlide = 0;

const heroImage = document.getElementById("heroImage");
const dots = document.querySelectorAll(".dot");

function changeSlide() {
    if (!heroImage || dots.length === 0) return;

    heroImage.classList.add("fade");

    setTimeout(() => {
        currentSlide = (currentSlide + 1) % heroImages.length;

        heroImage.src = heroImages[currentSlide];

        dots.forEach(dot => {
            dot.classList.remove("active");
        });

        dots[currentSlide].classList.add("active");
        heroImage.classList.remove("fade");

    }, 400);
}

if (heroImage && dots.length > 0) {
    setInterval(changeSlide, 5000);
}


/* ==========================
   INITIALIZE WEBSITE
========================== */

async function initializeWebsite() {
    try {
        await loadUserProgress();
    } catch (error) {
        console.error("Progress loading error:", error);
    }
}

initializeWebsite();