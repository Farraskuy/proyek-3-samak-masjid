<!-- Include stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

<div id="editor">
  <p>Hello World!</p>
  <p>Some initial <strong>bold</strong> text</p>
</div>

<button id="submit">Submit</button>

<!-- Include the Quill library -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

<script>
  const quill = new Quill('#editor', {
    theme: 'snow'
  });

  document.getElementById('submit').addEventListener('click', async () => {
    const content = quill.root.innerHTML; // Ambil isi editor sebagai HTML

    const response = await fetch('http://localhost:8000/api/posts', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        content: content
      })
    });

    const result = await response.json();
    console.log(result);
  });
</script>
