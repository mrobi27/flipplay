const noResult = document.getElementById("noResult");
const contentCard = document.getElementById("content-card");
const searchInput = document.getElementById("searchInput");
const loading = document.getElementById("loading");
const errorMessage = document.getElementById("errorMessage");

let showData = [];
let activeAura = "all";
let activeType = "anime";

async function fetchData(type) {
  if (type === "anime") {
    const response = await fetch(
      "https://kitsu.io/api/edge/anime?page[limit]=20"
    );

    if (!response.ok) {
      throw new Error("Gagal mengambil data anime");
    }

    const data = await response.json();

    return data.data.map(item => ({
      title:
        item.attributes.titles?.en ||
        item.attributes.titles?.en_jp ||
        item.attributes.titles?.ja_jp ||
        "Unknown Title",
      image: item.attributes.posterImage?.medium || "",
      rating: Number(item.attributes.averageRating) || 0,
      episodes: item.attributes.episodeCount || 0,
      type: "Anime"
    }));
  }

  if (type === "manga") {
    const response = await fetch(
      "https://kitsu.io/api/edge/manga?page[limit]=20"
    );

    if (!response.ok) {
      throw new Error("Gagal mengambil data manga");
    }

    const data = await response.json();

    return data.data.map(item => ({
      title:
        item.attributes.titles?.en ||
        item.attributes.titles?.en_jp ||
        item.attributes.titles?.ja_jp ||
        item.attributes.canonicalTitle ||
        "Unknown Title",
      image: item.attributes.posterImage?.medium || "",
      rating: Number(item.attributes.averageRating) || 0,
      episodes:
        item.attributes.chapterCount ||
        item.attributes.volumeCount ||
        0,
      type: "Manga"
    }));
  }

  if (type === "drama") {
    const response = await fetch(
      "https://api.tvmaze.com/shows?page=0"
    );

    if (!response.ok) {
      throw new Error("Gagal mengambil data drama");
    }

    const data = await response.json();

    return data
      .filter(item => item.genres?.includes("Drama"))
      .slice(0, 20)
      .map(item => ({
        title: item.name || "Unknown Title",
        image: item.image?.medium || "",
        rating: (item.rating?.average || 0) * 10,
        episodes: 0,
        type: "Drama"
      }));
  }

  return [];
}

async function loadData() {
  loading.classList.remove("hidden");
  errorMessage.classList.add("hidden");
  noResult.classList.add("hidden");

  try {
    showData = await fetchData(activeType);
    renderShows();
  } catch (error) {
    console.error(error);
    errorMessage.classList.remove("hidden");
    contentCard.innerHTML = "";
  } finally {
    loading.classList.add("hidden");
  }
}

function renderShows() {
  const keyword = searchInput.value.toLowerCase().trim();

  const filteredShows = showData.filter(item => {
    const matchesSearch = item.title
      .toLowerCase()
      .includes(keyword);

    let matchesAura = true;

    if (activeAura === "high-energy") {
      matchesAura = item.rating >= 80;
    } else if (activeAura === "melancholy") {
      matchesAura = item.rating < 75;
    } else if (activeAura === "binge") {
      matchesAura = item.episodes >= 12;
    }

    return matchesSearch && matchesAura;
  });

  if (filteredShows.length === 0) {
    noResult.classList.remove("hidden");
  } else {
    noResult.classList.add("hidden");
  }

  contentCard.innerHTML = filteredShows
    .map(item => {
      const rating = item.rating
        ? (item.rating / 10).toFixed(1)
        : "N/A";

      return `
        <div class="rounded-2xl bg-surface overflow-hidden border border-white/10">
          <img
            src="${item.image}"
            alt="${item.title}"
            class="w-full h-64 object-cover"
          >

          <div class="p-4">
            <h3 class="text-lg font-bold text-white">
              ${item.title}
            </h3>

            <p class="mt-2 text-sm text-gray-400">
              ${item.type}
            </p>

            <p class="mt-2 text-sm text-gray-300">
              ⭐ ${rating}
            </p>
          </div>
        </div>
      `;
    })
    .join("");
}

searchInput.addEventListener("input", renderShows);

document.querySelectorAll(".aura-btn").forEach(button => {
  button.addEventListener("click", () => {
    activeAura = button.dataset.aura;

    document.querySelectorAll(".aura-btn").forEach(btn => {
      btn.classList.remove("bg-brand");
      btn.classList.add("border", "border-white/10");
    });

    button.classList.add("bg-brand");
    button.classList.remove("border", "border-white/10");

    renderShows();
  });
});

document.querySelectorAll(".media-btn").forEach(button => {
  button.addEventListener("click", () => {
    activeType = button.dataset.type;

    document.querySelectorAll(".media-btn").forEach(btn => {
      btn.classList.remove("bg-brand");
      btn.classList.add("border", "border-white/10");
    });

    button.classList.add("bg-brand");
    button.classList.remove("border", "border-white/10");

    loadData();
  });
});

loadData();