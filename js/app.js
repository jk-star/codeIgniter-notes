// =============================
// Global Variables
// =============================

const chapterList = document.getElementById("chapter-list");
const content = document.getElementById("content");

const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");

const searchInput = document.getElementById("searchInput");

const progressBar = document.getElementById("readingProgress");
const contentBox = document.querySelector(".content-box");

const themeBtn = document.getElementById("themeBtn");

const tocList = document.getElementById("tocList");

const menuToggle = document.getElementById("menuToggle");

const sidebar = document.querySelector(".sidebar");

const menuIcon = document.querySelector(".menu-icon");

const backToTop = document.getElementById("backToTop");

const activeLink = document.querySelector(".toc a.active");

const breadcrumb = document.getElementById("breadcrumb");

const printBtn=document.getElementById("printBtn");

// console.log(progressBar);

let currentChapterIndex = 0;

function updateBreadcrumb() {

    const chapter = chapters[currentChapterIndex];

    breadcrumb.innerHTML = `
        🏠 Home
        <span> &gt; </span>
        JavaScript Tutorial
        <span> &gt; </span>
        <strong>${chapter.title}</strong>
    `;

}

// =============================
// Sidebar Generate
// =============================

function createSidebar() {

    chapterList.innerHTML = "";

    chapters.forEach((chapter, index) => {

        const li = document.createElement("li");

        const link = document.createElement("a");

        link.href = "#";

        link.textContent = `Chapter ${chapter.id} - ${chapter.title}`;

        link.dataset.index = index;

        link.addEventListener("click", (e) => {

            e.preventDefault();

            currentChapterIndex = index;

            loadChapter();
            updateURL();
            saveLastChapter();
            updateActiveTOC();
            updateBreadcrumb();

        });

        li.appendChild(link);
        chapterList.appendChild(li);

    });

}

// =============================
// Load Chapter
// =============================

async function loadChapter() {

    try {

        const chapter = chapters[currentChapterIndex];

        const response = await fetch(`chapters/${chapter.file}`);

        if (!response.ok) {

            throw new Error("Chapter Not Found");

        }

        const markdown = await response.text();

        content.innerHTML = marked.parse(markdown);

        generateTOC();

        setActiveChapter();

        updateNavigation();

        updateURL();

        saveLastChapter();

        updateBreadcrumb();

        // Highlight Code
        document.querySelectorAll("pre code").forEach((block) => {

            hljs.highlightElement(block);

        });

        // Copy Button

        document.querySelectorAll("pre").forEach((pre) => {

            const btn = document.createElement("button");

            btn.innerText = "Copy";
            btn.className = "copy-btn";
            btn.onclick = () => {

                navigator.clipboard.writeText(pre.innerText);

                btn.innerText = "Copied";

                setTimeout(() => {

                    btn.innerText = "Copy";

                }, 1500);

            };

            pre.appendChild(btn);

        });

        setActiveChapter();
        updateNavigation();

    }

    catch (error) {

        content.innerHTML = `<h2>${error.message}</h2>`;
        console.error(error);

    }

}


// =============================
// URL Update + Refresh Same Chapter
// Chapter 5 open karti ho
// Browser Refresh karti ho

// To wapas Chapter 1 open ho jayega.

// Professional documentation me aisa nahi hota.
// =============================

function updateURL() {
    const chapter = chapters[currentChapterIndex];
    const url = new URL(window.location);
    url.searchParams.set("chapter", chapter.id);
    window.history.replaceState({}, "", url);
}


// =============================
// Dark / Light Mode
// =============================
function loadTheme() {

    const savedTheme = localStorage.getItem("theme");

    if (savedTheme === "dark") {

        document.body.classList.add("dark-mode");
        themeBtn.innerHTML = "☀️ Light Mode";

    }

}

// =============================
// Active Sidebar
// =============================

function setActiveChapter() {

    const links = document.querySelectorAll("#chapter-list a");

    links.forEach((link) => {
        link.classList.remove("active");
    });

    links[currentChapterIndex].classList.add("active");

}

// =============================
// Navigation Buttons
// =============================

function updateNavigation() {

    // Previous

    if (currentChapterIndex === 0) {
        prevBtn.style.display = "none";

    } else {
        prevBtn.style.display = "inline-block";

    }

    // Next

    if (currentChapterIndex === chapters.length - 1) {
        nextBtn.style.display = "none";

    } else {
        nextBtn.style.display = "inline-block";

    }

}

// =============================
// Auto Table of Contents (TOC)

// Right side me automatically headings ka list banega.

// 📚 On This Page



// Aur click karne par us heading par scroll ho jayega.
// =============================

function generateTOC() {

    tocList.innerHTML = "";

    const headings = document.querySelectorAll("#content h2,#content h3");

    headings.forEach((heading, index) => {

        const id = "heading-" + index;

        heading.id = id;

        const li = document.createElement("li");

        li.innerHTML = `
            <a href="#${id}">
                ${heading.textContent}
            </a>
        `;

        tocList.appendChild(li);

    });

}


function updateActiveTOC() {

    const headings = document.querySelectorAll("#content h2, #content h3");
    const links = document.querySelectorAll("#tocList a");
    let currentHeading = "";

    headings.forEach((heading) => {

        const rect = heading.getBoundingClientRect();

        if (rect.top <= 120) {
            currentHeading = heading.id;
        }

    });

    links.forEach(link => {

        link.classList.remove("active");

        if (link.getAttribute("href") === "#" + currentHeading) {
            link.classList.add("active");
        }

    });

}

// =============================
// Remember Last Read Chapter
// =============================

function saveLastChapter() {

    localStorage.setItem(
        "lastChapter",
        chapters[currentChapterIndex].id
    );

}

// =============================
// Previous Button
// =============================

prevBtn.addEventListener("click", () => {

    if (currentChapterIndex > 0) {

        currentChapterIndex--;

        loadChapter();
        updateURL();
        saveLastChapter();
        updateActiveTOC();
        updateBreadcrumb();

    }

});

// =============================
// Next Button
// =============================

nextBtn.addEventListener("click", () => {

    if (currentChapterIndex < chapters.length - 1) {

        currentChapterIndex++;

        loadChapter();
        updateURL();
        saveLastChapter();
        updateActiveTOC();
        updateBreadcrumb();

    }

});



// =============================
// Init
// =============================

function init() {

    const params = new URLSearchParams(window.location.search);

    const chapterId = parseInt(params.get("chapter"));

    if (chapterId) {

        const index = chapters.findIndex(ch => ch.id === chapterId);

        if (index !== -1) {
            currentChapterIndex = index;
        }

    }

    createSidebar();
    loadChapter();
    saveLastChapter();
    updateActiveTOC();
    updateBreadcrumb();

}

searchInput.addEventListener("keyup", () => {

    const keyword = searchInput.value.toLowerCase();
    const links = document.querySelectorAll("#chapter-list a");

    links.forEach((link) => {

        if (link.textContent.toLowerCase().includes(keyword)) {
            link.parentElement.style.display = "";
        }

        else {
            link.parentElement.style.display = "none";
        }

    });

});

contentBox.addEventListener("scroll", () => {

    const scrollTop = contentBox.scrollTop;

    const scrollHeight =
        contentBox.scrollHeight - contentBox.clientHeight;

    const progress =
        (scrollTop / scrollHeight) * 100;

    progressBar.style.width = progress + "%";

});

themeBtn.addEventListener("click", () => {

    document.body.classList.toggle("dark-mode");

    if (document.body.classList.contains("dark-mode")) {

        localStorage.setItem("theme", "dark");
        themeBtn.innerHTML = "☀️ Light Mode";

    } else {

        localStorage.setItem("theme", "light");
        themeBtn.innerHTML = "🌙 Dark Mode";

    }

});



menuToggle.addEventListener("click", () => {

    sidebar.classList.toggle("show");
    menuToggle.classList.toggle("active");

    if (sidebar.classList.contains("show")) {
        menuIcon.innerHTML = "&#10005;";   // ✕

    } else {
        menuIcon.innerHTML = "&#9776;";    // ☰

    }

});

if (window.innerWidth < 992) {

    sidebar.classList.remove("show");
    menuToggle.classList.remove("active");
    menuIcon.innerHTML = "&#9776;";

}

contentBox.addEventListener("scroll", () => {

    // Progress Bar
    const scrollTop = contentBox.scrollTop;

    const scrollHeight =
        contentBox.scrollHeight - contentBox.clientHeight;

    const progress =
        (scrollTop / scrollHeight) * 100;

    progressBar.style.width = progress + "%";

    // Back To Top
    if (scrollTop > 300) {
        backToTop.classList.add("show");
    } else {
        backToTop.classList.remove("show");
    }

});

backToTop.addEventListener("click", () => {
    contentBox.scrollTo({
        top: 0,
        behavior: "smooth"
    });
});

contentBox.addEventListener("scroll", () => {
    updateActiveTOC();
});


if (activeLink) {
    activeLink.scrollIntoView({
        block: "nearest",
        behavior: "smooth"
    });

}

printBtn.addEventListener("click", () => {

    const content = document.getElementById("content").innerHTML;

    const printWindow = window.open("", "_blank");

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>

            <title>Print</title>

            <link rel="stylesheet" href="css/style.css">

            <style>

                body{
                    padding:40px;
                    font-family:Arial,sans-serif;
                    line-height:1.7;
                }

                img{
                    max-width:100%;
                    height:auto;
                }

                pre{
                    white-space:pre-wrap;
                    word-break:break-word;
                    border:1px solid #ddd;
                    padding:15px;
                    border-radius:8px;
                }

                table{
                    width:100%;
                    border-collapse:collapse;
                }

                table,
                th,
                td{
                    border:1px solid #999;
                }

                th,
                td{
                    padding:10px;
                }

            </style>

        </head>

        <body>

            ${content}

        </body>

        </html>
    `);

    printWindow.document.close();

    printWindow.onload = function(){

        printWindow.focus();

        printWindow.print();

    };

    printWindow.onafterprint = function(){

        printWindow.close();

    };

});

init();