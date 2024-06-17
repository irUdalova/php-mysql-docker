import { filterTag, addTags } from "./tag.js";

let tags = [];
let tagsDB = application.tags;
// console.log("tagsDB", tagsDB);

let tagsList = [...tagsDB];
console.log(tagsList, "tagsList");

const controlTagContainer = document.querySelector(".control-tags-container");
if (controlTagContainer) {
  // init tags arrays if data exists
  const tagsCurrent = controlTagContainer.dataset.current; // [id, id]
  if (tagsCurrent) {
    //array of tags
    tags = tagsCurrent.split(",").map((tagId) => {
      return tagsList.find((el) => el.id.toString() === tagId).tag;
    });
    addTags(tags, controlTagContainer);
  }

  // function addTags(inputTags, element) {
  //   reset();
  //   inputTags
  //     .slice()
  //     .reverse()
  //     .forEach(function (tag) {
  //       let tagEl = createTag(tag);
  //       element.prepend(tagEl);
  //     });
  // }

  function selectTag(e) {
    const element = e.target;
    const tagItem = element.textContent;
    tags.push(filterTag(tagItem));

    addTags(tags, controlTagContainer);

    element.classList.add("selected");
  }

  function resetTagsList() {
    document.querySelectorAll(".tag-list-item").forEach(function (item) {
      item.parentElement.removeChild(item);
    });
  }

  function renderTagsList(tagsArray) {
    resetTagsList();

    let list = document.querySelector(".tags-dropdown-list");
    if (!list) {
      list = document.createElement("ul");
      list.setAttribute("class", "tags-dropdown-list");
    }

    tagsArray.forEach((item) => {
      let itemList = document.createElement("li");
      itemList.setAttribute("class", "tag-list-item");
      itemList.setAttribute("data-tag", item.tag);
      itemList.innerHTML = item.tag;

      if (tags.includes(item.tag)) {
        itemList.classList.add("selected");
      }

      list.append(itemList);
    });
    controlTagContainer.appendChild(list);
  }

  if (tagsList.length) {
    renderTagsList(tagsList);
    document.querySelectorAll(".tag-list-item").forEach(function (item) {
      item.addEventListener("click", selectTag);
    });
  }

  controlTagContainer.addEventListener("click", function (e) {
    controlTagContainer.classList.add("active");
  });

  document.addEventListener("click", function (e) {
    if (!e.target.closest(".control-tags-container.active")) {
      controlTagContainer.classList.remove("active");
    }

    if (e.target.innerHTML === "close") {
      const value = e.target.getAttribute("data-item");

      const index = tags.indexOf(value);
      tags = [...tags.slice(0, index), ...tags.slice(index + 1)];
      addTags(tags, controlTagContainer);

      const closedItem = document.querySelector(`[data-tag="${value}"]`);

      if (closedItem) {
        closedItem.classList.remove("selected");
      }
    }
  });

  //submit form
  const controlBarForm = document.querySelector(".control-bar-form");

  if (controlBarForm) {
    controlBarForm.addEventListener("submit", function () {
      if (tags.length) {
        //array og tag names
        tags
          .map((tag) => {
            return tagsList.find((el) => el.tag === tag).id;
          })
          .forEach(function (tag, index) {
            let hiddenInput = document.createElement("input");
            hiddenInput.setAttribute("type", "hidden");
            hiddenInput.setAttribute("name", `tags[]`);
            hiddenInput.value = tag;
            controlTagContainer.appendChild(hiddenInput);
          });
      }
    });
  }

  //sort params
  const sortContainer = document.querySelector("#sort");
  if (sortContainer) {
    sortContainer.addEventListener("change", function (e) {
      controlBarForm.requestSubmit();
    });
  }
}
