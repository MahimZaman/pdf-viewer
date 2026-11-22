document.addEventListener("DOMContentLoaded", function () {
  let pdfDoc = null;
  let currentPage = 1;
  const scale = 1.5;

  pdfjsLib.GlobalWorkerOptions.workerSrc =
    "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

  const wrapper = document.querySelector(".spv-pdf-wrapper");
  const canvas = document.querySelector(".spv-pdf-canvas");
  const ctx = canvas.getContext("2d");

  const pdfUrl = wrapper.dataset.pdf;

  function renderPage(num) {
    pdfDoc.getPage(num).then((page) => {
      const viewport = page.getViewport({ scale });
      canvas.width = viewport.width;
      canvas.height = viewport.height;

      page.render({
        canvasContext: ctx,
        viewport: viewport,
      });
    });
  }

  pdfjsLib.getDocument(pdfUrl).promise.then((pdf) => {
    pdfDoc = pdf;
    renderPage(currentPage);
  });

  document.getElementById("spv-prev").onclick = () => {
    if (currentPage <= 1) return;
    currentPage--;
    renderPage(currentPage);
  };

  document.getElementById("spv-next").onclick = () => {
    if (currentPage >= pdfDoc.numPages) return;
    currentPage++;
    renderPage(currentPage);
  };
});
