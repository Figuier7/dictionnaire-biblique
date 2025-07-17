
console.log("✅ Interface unifiée du dictionnaire chargée (correction startsWith)");

const sources = {
  BYM: "/wp-content/uploads/dictionnaires/lexique-bym.json",
  Easton: "/wp-content/uploads/dictionnaires/eastons.json"
};

let allData = {};
let currentDict = "BYM";
let currentLetter = "A";
let currentPage = 1;
const itemsPerPage = 30;

function normalizeInput(str) {
  return (str || "").normalize("NFD").replace(/[̀-ͯ]/g, "").trim().toUpperCase();
}

function initDictionaryApp() {
  const tabs = document.querySelectorAll(".dict-tab");
  const alpha = document.getElementById("alphabet-selector");
  const list = document.getElementById("word-list");
  const content = document.getElementById("dictionary-content");
  const search = document.createElement("input");
  search.placeholder = "🔍 Rechercher un mot...";
  search.id = "dictionary-search";
  list.before(search);

  function resetView() {
    list.style.display = "block";
    content.innerHTML = "";
    search.value = "";
  }

  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      tabs.forEach(t => t.classList.remove("active"));
      tab.classList.add("active");
      currentDict = tab.dataset.dict;
      currentPage = 1;
      resetView();
      buildAlphabet(currentDict);
      listWords(currentDict, currentLetter, currentPage);
    });
  });

  function buildAlphabet(dict) {
  if (window.innerWidth <= 640) {
    alpha.innerHTML = ""; // sécurité : forcer vide
    return;
  }

  const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");
  alpha.innerHTML = "";
  letters.forEach(letter => {
    const btn = document.createElement("button");
    btn.textContent = letter;
    btn.addEventListener("click", () => {
      currentLetter = letter;
      currentPage = 1;
      listWords(dict, letter, currentPage);
    });
    alpha.appendChild(btn);
  });
}


  function listWords(dict, letter, page = 1) {
    const cleanLetter = normalizeInput(letter);
    const data = allData[dict].filter(e =>
      normalizeInput(e.mot).startsWith(cleanLetter)
    );
    const total = Math.ceil(data.length / itemsPerPage);
    const start = (page - 1) * itemsPerPage;
    const shown = data.slice(start, start + itemsPerPage);
    const html = shown.map(e => `<a href="#${dict}/${e.mot}">${e.mot}</a>`).join("");
    const nav = total > 1 ? `<div class='pagination'>${Array.from({ length: total }, (_, i) => `<button class="page-btn" data-page="${i + 1}">${i + 1}</button>`).join("")}</div>` : "";
    list.innerHTML = html + nav;
    list.style.display = "block";
    document.querySelectorAll(".page-btn").forEach(b => {
      b.addEventListener("click", () => {
        currentPage = parseInt(b.dataset.page);
        listWords(currentDict, currentLetter, currentPage);
      });
    });
  }

  function displayDefinition(dict, mot) {
    const found = allData[dict].find(e => normalizeInput(e.mot) === normalizeInput(mot));
    if (!found) {
      content.innerHTML = "<p>Mot introuvable.</p>";
      return;
    }
    list.style.display = "none";
    
    const dictName = dict === "BYM" ? "Dictionnaire biblique de la BYM" : dict === "Easton" ? "Easton's Bible Dictionary" : dict;
    content.innerHTML = `
      <p><a href="#" id="back">← Retour à la liste</a></p>
      <h2>${found.mot} — ${dictName}</h2>
      ${marked.parse(found.definition)}
    `;

    document.getElementById("back").onclick = e => {
      e.preventDefault();
      list.style.display = "block";
      content.innerHTML = "";
    };
  }

  function handleHash() {
    const hash = decodeURIComponent(location.hash.slice(1));
    if (!hash.includes("/")) return;
    const [dict, mot] = hash.split("/");
    if (sources[dict]) displayDefinition(dict, mot);
  }

  function handleSearch() {
    const val = normalizeInput(this.value.trim());
    if (val.length < 2) return;
    const results = allData[currentDict]
      .map(e => ({
        mot: e.mot,
        score: normalizeInput(e.mot).includes(val) ? 0 : 1
      }))
      .sort((a, b) => a.score - b.score)
      .slice(0, 50);
    list.innerHTML = results.map(e => `<a href="#${currentDict}/${e.mot}">${e.mot}</a>`).join("");
    content.innerHTML = "";
    list.style.display = "block";
  }

  Promise.all(Object.entries(sources).map(async ([key, url]) => {
    const res = await fetch(url);
    const data = await res.json();
    allData[key] = Array.isArray(data)
      ? data.map(e => ({ mot: e.mot || e.term || "", definition: e.definition || "" }))
      : Object.entries(data).map(([mot, def]) => ({ mot, definition: def }));
  })).then(() => {
    buildAlphabet(currentDict);
    listWords(currentDict, currentLetter, currentPage);
    handleHash();
    search.addEventListener("input", handleSearch);
    window.addEventListener("hashchange", handleHash);
  });
}

document.addEventListener("DOMContentLoaded", initDictionaryApp);
