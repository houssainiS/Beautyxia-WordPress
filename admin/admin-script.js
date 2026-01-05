/* Explicitly use window.jQuery to resolve linter issue */
;(($ = window.jQuery) => {
  // Copy shortcode button
  $(".fa-copy-btn").on("click", function () {
    const textToCopy = $(this).data("copy")

    navigator.clipboard
      .writeText(textToCopy)
      .then(
        function () {
          const originalText = $(this).text()
          $(this).text("✓ Copied!")

          setTimeout(
            (el) => {
              $(el).text(originalText)
            },
            2000,
            this,
          )
        }.bind(this),
      )
      .catch((err) => {
        alert("Failed to copy: " + err)
      })
  })
})()
