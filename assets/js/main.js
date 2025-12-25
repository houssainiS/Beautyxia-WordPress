// Main Face Analysis Plugin JavaScript
let currentLanguage = "en"
let lastAnalysisData = null
let uploadedFile = null
let webcamStream = null

document.addEventListener("DOMContentLoaded", () => {
  const fileInput = document.getElementById("picture")
  const preview = document.getElementById("preview-image")
  const previewPlaceholder = document.getElementById("preview-placeholder")
  const webcamBtn = document.getElementById("webcam-btn")
  const captureBtn = document.getElementById("capture-btn")
  const stopCameraBtn = document.getElementById("stop-camera-btn")
  const loadingSection = document.getElementById("loading-section")
  const consentCheckbox = document.getElementById("consent")
  const analyzeBtn = document.getElementById("analyze-btn")
  const webcamSection = document.getElementById("webcam-section")
  const webcamVideo = document.getElementById("webcam-video")
  const webcamCanvas = document.getElementById("webcam-canvas")

  const translations = window.translations || {}

  const faceAnalysisConfig = {
    apiEndpoint: "http://127.0.0.1:8000/upload/",
  }

  function updateAnalyzeButton() {
    const hasFile = uploadedFile !== null
    const hasConsent = consentCheckbox.checked
    analyzeBtn.disabled = !(hasFile && hasConsent)
  }

  function translatePage(language) {
    currentLanguage = language

    const elements = document.querySelectorAll("[data-translate]")
    elements.forEach((element) => {
      const key = element.getAttribute("data-translate")
      if (translations[language] && translations[language][key]) {
        element.textContent = translations[language][key]
      }
    })

    const appContainer = document.querySelector(".my-app-container")
    if (appContainer) {
      appContainer.dir = language === "ar" ? "rtl" : "ltr"
    }

    document.documentElement.lang = language
    localStorage.setItem("preferredLanguage", language)

    if (lastAnalysisData && typeof regenerateTipsForLanguage === "function") {
      const newTips = regenerateTipsForLanguage(lastAnalysisData, language)
      if (typeof displayTips === "function") {
        displayTips(newTips)
      }
    }
  }

  const storedLanguage = localStorage.getItem("preferredLanguage") || "en"

  document.querySelectorAll(".lang-selector-btn").forEach((btn) => {
    btn.classList.remove("active")
    if (btn.dataset.lang === storedLanguage) {
      btn.classList.add("active")
    }
  })

  translatePage(storedLanguage)

  const langBtns = document.querySelectorAll(".lang-selector-btn")
  langBtns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault()
      langBtns.forEach((b) => b.classList.remove("active"))
      btn.classList.add("active")
      translatePage(btn.dataset.lang)
    })
  })

  if (fileInput) {
    fileInput.addEventListener("change", function () {
      const file = this.files[0]
      if (file) {
        preview.src = URL.createObjectURL(file)
        preview.style.display = "block"
        previewPlaceholder.style.display = "none"
        uploadedFile = file
        updateAnalyzeButton()
        hideResults()
        hideWebcam()
      }
    })
  }

  if (consentCheckbox) {
    consentCheckbox.addEventListener("change", () => {
      updateAnalyzeButton()
    })
  }

  if (webcamBtn) {
    webcamBtn.addEventListener("click", () => {
      startWebcam()
    })
  }

  if (captureBtn) {
    captureBtn.addEventListener("click", () => {
      capturePhoto()
    })
  }

  if (stopCameraBtn) {
    stopCameraBtn.addEventListener("click", () => {
      hideWebcam()
    })
  }

  function startWebcam() {
    navigator.mediaDevices
      .getUserMedia({ video: { facingMode: "user" } })
      .then((stream) => {
        webcamStream = stream
        webcamVideo.srcObject = stream
        webcamSection.style.display = "flex"
        webcamBtn.style.display = "none"
        captureBtn.style.display = "flex"
        stopCameraBtn.style.display = "flex"
      })
      .catch((err) => {
        console.error("Error accessing webcam:", err)
        alert("Unable to access webcam. Please check permissions.")
      })
  }

  function capturePhoto() {
    const context = webcamCanvas.getContext("2d")
    context.drawImage(webcamVideo, 0, 0, 256, 256)
    webcamCanvas.toBlob((blob) => {
      uploadedFile = blob
      preview.src = URL.createObjectURL(blob)
      preview.style.display = "block"
      previewPlaceholder.style.display = "none"
      updateAnalyzeButton()
      hideWebcam()
    })
  }

  function hideWebcam() {
    if (webcamStream) {
      webcamStream.getTracks().forEach((track) => track.stop())
      webcamStream = null
    }
    webcamSection.style.display = "none"
    webcamBtn.style.display = "flex"
    captureBtn.style.display = "none"
    stopCameraBtn.style.display = "none"
  }

  function hideResults() {
    document.getElementById("results-section").style.display = "none"
    document.getElementById("analysis-images").style.display = "none"
    document.getElementById("tips-section").style.display = "none"
    document.getElementById("feedbackSection").style.display = "none"
  }

  function displayResults(data) {
    lastAnalysisData = data
    console.log("[v0] Displaying results:", data)

    // Show results section
    const resultsSection = document.getElementById("results-section")
    resultsSection.style.display = "block"

    // Update face image
    if (data.cropped_face) {
      document.getElementById("analyzed-face").src = data.cropped_face
    }

    // Update skin type
    if (data.skin_type) {
      document.getElementById("skin-type-value").textContent = data.skin_type
      const skinProbs = document.getElementById("skin-probabilities")
      skinProbs.innerHTML = ""

      const skinTypes = ["Dry", "Normal", "Oily"]
      if (data.type_probs && data.type_probs.length > 0) {
        skinTypes.forEach((type, index) => {
          const prob = (data.type_probs[index] * 100).toFixed(1)
          const barHTML = `
            <div class="probability-bar">
              <div class="prob-label">
                <span>${type}</span>
                <span>${prob}%</span>
              </div>
              <div class="prob-track">
                <div class="prob-fill" style="width: ${prob}%"></div>
              </div>
            </div>
          `
          skinProbs.innerHTML += barHTML
        })
      }
    }

    // Update eye colors
    if (data.left_eye_color) {
      document.getElementById("left-eye-color").textContent = data.left_eye_color
    }
    if (data.right_eye_color) {
      document.getElementById("right-eye-color").textContent = data.right_eye_color
    }

    // Update acne analysis
    if (data.acne_pred) {
      document.getElementById("acne-level").textContent = data.acne_pred
      const confidence = (data.acne_confidence * 100).toFixed(1)
      document.getElementById("acne-confidence").textContent = `${confidence}%`
      document.getElementById("acne-confidence-fill").style.width = `${confidence}%`
    }

    if (data.yolo_boxes && data.yolo_boxes.length > 0) {
      const detectedIssues = document.getElementById("detected-issues")
      detectedIssues.innerHTML = ""

      // Group issues by class name and calculate average confidence
      const issueGroups = {}
      data.yolo_boxes.forEach((box) => {
        let issueClass = "Detected Issue"
        if (box.class !== undefined && box.class !== null) {
          issueClass = String(box.class)
        } else if (box.name !== undefined && box.name !== null) {
          issueClass = String(box.name)
        } else if (box.label !== undefined && box.label !== null) {
          issueClass = String(box.label)
        }

        if (!issueGroups[issueClass]) {
          issueGroups[issueClass] = {
            count: 0,
            confidences: [],
            totalConfidence: 0,
          }
        }
        issueGroups[issueClass].count += 1
        const confidence = box.confidence !== undefined ? box.confidence * 100 : 0
        issueGroups[issueClass].confidences.push(confidence)
        issueGroups[issueClass].totalConfidence += confidence
      })

      if (data.segmentation_results && data.segmentation_results.length > 0) {
        data.segmentation_results.forEach((seg) => {
          let issueClass = seg.label || "Detected Issue"
          issueClass = String(issueClass).toLowerCase()

          if (!issueGroups[issueClass]) {
            issueGroups[issueClass] = {
              count: 0,
              confidences: [],
              totalConfidence: 0,
            }
          }
          issueGroups[issueClass].count += 1
          const confidence = seg.confidence !== undefined ? seg.confidence * 100 : 0
          issueGroups[issueClass].confidences.push(confidence)
          issueGroups[issueClass].totalConfidence += confidence
        })
      }

      // Display aggregated issues
      Object.entries(issueGroups).forEach(([issueClass, data]) => {
        const avgConfidence = (data.totalConfidence / data.count).toFixed(1)
        const areaText = data.count > 1 ? "areas" : "area"
        const formattedClass = issueClass.replace(/_/g, "_").toLowerCase()

        const issueHTML = `
          <div class="issue-item">
            <div class="issue-info">
              <div class="issue-name">${formattedClass}</div>
              <div class="issue-count">${data.count} ${areaText}</div>
            </div>
            <div class="issue-confidence">
              <div class="confidence-bar">
                <div class="confidence-fill" style="width: ${avgConfidence}%"></div>
              </div>
              <span class="confidence-text">${avgConfidence}%</span>
            </div>
          </div>
        `
        detectedIssues.innerHTML += issueHTML
      })
    } else {
      const detectedIssues = document.getElementById("detected-issues")
      detectedIssues.innerHTML = `
        <div class="no-issues-message">
          <div class="no-issues-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 12l2 2 4-4"/>
            </svg>
          </div>
          <span class="no-issues-text">${translations[currentLanguage].no_issues || "No significant issues detected"}</span>
        </div>
      `
    }

    // Show analysis images
    if (data.yolo_annotated || data.segmentation_overlay) {
      const analysisImages = document.getElementById("analysis-images")
      analysisImages.style.display = "block"

      if (data.yolo_annotated) {
        const yoloImg = document.getElementById("yolo-annotated-image")
        yoloImg.src = data.yolo_annotated
        yoloImg.style.display = "block"
        yoloImg.onerror = () => console.error("Failed to load YOLO image")
      }

      if (data.segmentation_overlay) {
        const segImg = document.getElementById("segmentation-overlay-image")
        segImg.src = data.segmentation_overlay
        segImg.style.display = "block"
        segImg.style.visibility = "visible"
        segImg.style.opacity = "1"
        segImg.onerror = () => console.error("Failed to load segmentation image")
      }
    }

    // Generate and display tips
    if (typeof window.generateTips === "function") {
      const tips = window.generateTips(data)
      displayTips(tips)
    }

    // Show feedback section
    document.getElementById("feedbackSection").style.display = "block"

    // Scroll to results
    setTimeout(() => {
      resultsSection.scrollIntoView({ behavior: "smooth" })
    }, 300)
  }

  function displayTips(tips) {
    const tipsContent = document.getElementById("tips-content")
    tipsContent.innerHTML = ""

    if (tips && tips.length > 0) {
      const tipsSection = document.getElementById("tips-section")
      tipsSection.style.display = "block"

      tips.forEach((tip) => {
        const tipHTML = `
          <div class="tip-item">
            <div class="tip-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 19v2m0-18v2m15-4h-2m-18 0H1m17-7h-2m-12 0H3m17.657 17.657l-1.414-1.414m-12.728 0l-1.414 1.414m17.657-17.657l-1.414 1.414m-12.728 0l-1.414-1.414"/>
              </svg>
            </div>
            <div class="tip-text">${tip}</div>
          </div>
        `
        tipsContent.innerHTML += tipHTML
      })
    }
  }

  function regenerateTipsForLanguage(data, language) {
    // Implementation for regenerating tips
    return []
  }

  function initializeFeedbackHandlers() {
    const likeBtn = document.getElementById("likeBtn")
    const dislikeBtn = document.getElementById("dislikeBtn")
    const feedbackMessage = document.getElementById("feedbackMessage")

    if (likeBtn) {
      likeBtn.addEventListener("click", () => {
        feedbackMessage.textContent = translations[currentLanguage].feedback_thank_you || "Thank you for your feedback!"
        feedbackMessage.style.display = "block"
        likeBtn.disabled = true
        dislikeBtn.disabled = true
      })
    }

    if (dislikeBtn) {
      dislikeBtn.addEventListener("click", () => {
        feedbackMessage.textContent = translations[currentLanguage].feedback_thank_you || "Thank you for your feedback!"
        feedbackMessage.style.display = "block"
        likeBtn.disabled = true
        dislikeBtn.disabled = true
      })
    }
  }

  if (analyzeBtn) {
    analyzeBtn.addEventListener("click", () => {
      if (!uploadedFile || !consentCheckbox.checked) {
        return
      }

      const formData = new FormData()
      formData.append("photo", uploadedFile)

      hideResults()
      loadingSection.style.display = "block"
      analyzeBtn.disabled = true
      analyzeBtn.querySelector(".button-text").textContent =
        translations[currentLanguage].analyzing_title || "Analyzing..."

      fetch(faceAnalysisConfig.apiEndpoint, {
        method: "POST",
        body: formData,
      })
        .then((response) => {
          const contentType = response.headers.get("content-type")
          if (!contentType || !contentType.includes("application/json")) {
            return response.text().then((text) => {
              throw new Error(`Server returned ${contentType || "unknown content type"} instead of JSON`)
            })
          }
          return response.json()
        })
        .then((data) => {
          loadingSection.style.display = "none"
          updateAnalyzeButton()
          analyzeBtn.querySelector(".button-text").textContent =
            translations[currentLanguage].analyze_face || "Analyze Face"

          if (data.error) {
            console.error("API Error:", data.error)
          } else {
            displayResults(data)
          }
        })
        .catch((err) => {
          loadingSection.style.display = "none"
          updateAnalyzeButton()
          analyzeBtn.querySelector(".button-text").textContent =
            translations[currentLanguage].analyze_face || "Analyze Face"
          console.error("Analysis failed:", err)
        })
    })
  }

  initializeFeedbackHandlers()
})
