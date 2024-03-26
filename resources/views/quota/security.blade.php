<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>防伪码校验</title>
  <script src="{{ config('filesystems.disks.qcloud.url') }}/js/cdnjs.cloudflare.com_ajax_libs_pdf.js_2.6.347_pdf.min.js"></script>
<script src="{{ config('filesystems.disks.qcloud.url') }}/js/cdnjs.cloudflare.com_ajax_libs_pdf.js_2.6.347_pdf.worker.min.js"></script>
</head>
<body>
<canvas id="the-canvas" style="direction: ltr;"></canvas>
<script id="script">
  /* pdf url */
  const url = '{{ config('filesystems.disks.qcloud.url') . $quotation->pdf }}';
  const loadingTask = pdfjsLib.getDocument(url);
  (async () => {
    const pdf = await loadingTask.promise;
    const page = await pdf.getPage(1);
    const scale = 1.6;
    const viewport = page.getViewport({ scale });
    const outputScale = window.devicePixelRatio || 1;
    const canvas = document.getElementById("the-canvas");
    const context = canvas.getContext("2d");
    canvas.width = Math.floor(viewport.width * outputScale);
    canvas.height = Math.floor(viewport.height * outputScale);
    canvas.style.width = Math.floor(viewport.width) + "px";
    canvas.style.height = Math.floor(viewport.height) + "px";
    const transform = outputScale !== 1
      ? [outputScale, 0, 0, outputScale, 0, 0]
      : null;
    const renderContext = {
      canvasContext: context,
      transform,
      viewport,
    };
    page.render(renderContext);
  })();
</script>
</body>
</html>
