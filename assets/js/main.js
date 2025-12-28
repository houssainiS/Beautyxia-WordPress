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
  const productRecommendations = document.getElementById('product-recommendations')
  const activeFiltersDiv = document.getElementById('active-filters')
  const productItems = document.querySelectorAll('.product-item')
  const noProductsDiv = document.getElementById('no-products')

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

    if (lastAnalysisData && typeof window.regenerateTipsForLanguage === "function") {
      const newTips = window.regenerateTipsForLanguage(lastAnalysisData, language)
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
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      alert("Camera access is not supported by this browser or connection. Please ensure you are using HTTPS.");
      return;
    }

    navigator.mediaDevices
      .getUserMedia({ 
        video: { 
          facingMode: "user",
          width: { ideal: 1280 },
          height: { ideal: 720 }
        } 
      })
      .then((stream) => {
        webcamStream = stream
        webcamVideo.srcObject = stream
        webcamVideo.setAttribute("playsinline", true) 
        webcamVideo.play()
        
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
    
    webcamCanvas.width = 256
    webcamCanvas.height = 256
    
    const videoWidth = webcamVideo.videoWidth
    const videoHeight = webcamVideo.videoHeight
    const size = Math.min(videoWidth, videoHeight)
    const xOffset = (videoWidth - size) / 2
    const yOffset = (videoHeight - size) / 2

    context.drawImage(webcamVideo, xOffset, yOffset, size, size, 0, 0, 256, 256)
    
    webcamCanvas.toBlob((blob) => {
      uploadedFile = blob
      preview.src = URL.createObjectURL(blob)
      preview.style.display = "block"
      previewPlaceholder.style.display = "none"
      updateAnalyzeButton()
      hideWebcam()
    }, "image/jpeg", 0.95)
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
    const sections = [
      "results-section", 
      "analysis-images", 
      "tips-section", 
      "feedbackSection",
      "product-recommendations"
    ]
    
    sections.forEach(id => {
      const el = document.getElementById(id)
      if(el) el.style.display = "none"
    })
  }

  function displayResults(data) {
    lastAnalysisData = data
    console.log("[v0] Displaying results:", data)

    const resultsSection = document.getElementById("results-section")
    resultsSection.style.display = "block"

    if (data.cropped_face) {
      document.getElementById("analyzed-face").src = data.cropped_face
    }

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

    if (data.left_eye_color) {
      document.getElementById("left-eye-color").textContent = data.left_eye_color
    }
    if (data.right_eye_color) {
      document.getElementById("right-eye-color").textContent = data.right_eye_color
    }

    if (data.acne_pred !== undefined) {
      document.getElementById("acne-level").textContent = data.acne_pred
      const confidence = (data.acne_confidence * 100).toFixed(1)
      document.getElementById("acne-confidence").textContent = `${confidence}%`
      document.getElementById("acne-confidence-fill").style.width = `${confidence}%`
    }

    const detectedIssues = document.getElementById("detected-issues")
    if (detectedIssues) {
      detectedIssues.innerHTML = ""
      const issueGroups = {}

      if (data.yolo_boxes && data.yolo_boxes.length > 0) {
        data.yolo_boxes.forEach((box) => {
          let issueClass = box.label || box.name || String(box.class) || "Detected Issue"
          if (!issueGroups[issueClass]) {
            issueGroups[issueClass] = { count: 0, totalConfidence: 0 }
          }
          issueGroups[issueClass].count += 1
          issueGroups[issueClass].totalConfidence += (box.confidence || 0) * 100
        })
      }

      if (data.segmentation_results && data.segmentation_results.length > 0) {
        data.segmentation_results.forEach((seg) => {
          let issueClass = seg.label || "Detected Issue"
          issueClass = String(issueClass).toLowerCase()
          if (!issueGroups[issueClass]) {
            issueGroups[issueClass] = { count: 0, totalConfidence: 0 }
          }
          issueGroups[issueClass].count += 1
          issueGroups[issueClass].totalConfidence += (seg.confidence || 0) * 100
        })
      }

      if (Object.keys(issueGroups).length > 0) {
        Object.entries(issueGroups).forEach(([issueClass, stats]) => {
          const avgConfidence = (stats.totalConfidence / stats.count).toFixed(1)
          const areaText = stats.count > 1 ? "areas" : "area"
          const formattedClass = issueClass.replace(/_/g, " ").replace(/\b\w/g, l => l.toUpperCase())

          const issueHTML = `
            <div class="issue-item">
              <div class="issue-info">
                <div class="issue-name">${formattedClass}</div>
                <div class="issue-count">${stats.count} ${areaText}</div>
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
        detectedIssues.innerHTML = `
          <div class="no-issues-message">
            <div class="no-issues-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 12l2 2 4-4"/>
              </svg>
            </div>
            <span class="no-issues-text">${translations[currentLanguage].no_issues_detected || "No significant issues detected"}</span>
          </div>
        `
      }
    }

    if (data.yolo_annotated || data.segmentation_overlay) {
      const analysisImages = document.getElementById("analysis-images")
      analysisImages.style.display = "block"

      if (data.yolo_annotated) {
        const yoloImg = document.getElementById("yolo-annotated-image")
        yoloImg.src = data.yolo_annotated
        yoloImg.style.display = "block"
      }

      if (data.segmentation_overlay) {
        const segImg = document.getElementById("segmentation-overlay-image")
        segImg.src = data.segmentation_overlay
        segImg.style.display = "block"
      }
    }

    if (typeof window.generateTips === "function") {
      const tips = window.generateTips(data)
      displayTips(tips)
    }

    // Trigger Product Recommendations (WooCommerce Logic)
    showProductRecommendations(data)

    document.getElementById("feedbackSection").style.display = "block"

    setTimeout(() => {
      resultsSection.scrollIntoView({ behavior: "smooth" })
    }, 300)
  }

  function displayTips(tips) {
    const tipsContent = document.getElementById("tips-content")
    if(!tipsContent) return
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

  // --- NEW: WooCommerce Product Recommendations Logic ---
  function showProductRecommendations(analysisData) {
    if(!activeFiltersDiv || !productRecommendations) return;

    const filters = [];
    
    // 1. Add Skin Type
    if (analysisData.skin_type) {
      filters.push(analysisData.skin_type.toLowerCase());
    }
    
    // 2. Add YOLO detections
    if (analysisData.yolo_boxes && analysisData.yolo_boxes.length > 0) {
      const detectedIssues = [...new Set(analysisData.yolo_boxes.map(box => 
        (box.label || box.class || '').toLowerCase()
      ))];
      filters.push(...detectedIssues);
    }
    
    // 3. Add Segmentation results
    if (analysisData.segmentation_results) {
      let segmentationIssues = [];
      if (Array.isArray(analysisData.segmentation_results)) {
        segmentationIssues = analysisData.segmentation_results
          .map(result => {
            if (typeof result === 'string') {
              return result;
            } else if (result && typeof result === 'object') {
              return (result.class || result.label || result.name || '').toString();
            }
            return '';
          })
          .filter(issue => issue.length > 0)
          .map(issue => issue.toLowerCase());
      }
      filters.push(...segmentationIssues);
    }
    
    // 4. Add Acne
    if (analysisData.acne_pred && parseInt(analysisData.acne_pred) > 0) {
      filters.push('acne');
    }
    
    const uniqueFilters = [...new Set(filters)];
    
    // Display active filters
    activeFiltersDiv.innerHTML = uniqueFilters.map(filter => 
      `<span class="filter-tag">${filter}</span>`
    ).join('');

    let visibleCount = 0;
    
    // Filter product items
    productItems.forEach(item => {
      const productTags = (item.dataset.tags || '').toLowerCase();
      const productTitle = (item.dataset.title || '').toLowerCase();
      
      const isRelevant = uniqueFilters.some(filter => 
        productTags.includes(filter) || productTitle.includes(filter)
      );
      
      if (isRelevant) {
        item.style.display = 'block';
        visibleCount++;
      } else {
        item.style.display = 'none';
      }
    });

    if (visibleCount === 0) {
      noProductsDiv.style.display = 'block';
    } else {
      noProductsDiv.style.display = 'none';
    }
    
    productRecommendations.style.display = 'block';
  }

  function initializeFeedbackHandlers() {
    const likeBtn = document.getElementById("likeBtn")
    const dislikeBtn = document.getElementById("dislikeBtn")
    const feedbackMessage = document.getElementById("feedbackMessage")

    const handleFeedback = () => {
        feedbackMessage.textContent = translations[currentLanguage].feedback_thank_you || "Thank you for your feedback!"
        feedbackMessage.style.display = "block"
        if(likeBtn) likeBtn.disabled = true
        if(dislikeBtn) dislikeBtn.disabled = true
    }

    if (likeBtn) likeBtn.addEventListener("click", handleFeedback)
    if (dislikeBtn) dislikeBtn.addEventListener("click", handleFeedback)
  }

  if (analyzeBtn) {
    analyzeBtn.addEventListener("click", () => {
      if (!uploadedFile || !consentCheckbox.checked) return

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
      .then((response) => response.json())
      .then((data) => {
        loadingSection.style.display = "none"
        updateAnalyzeButton()
        analyzeBtn.querySelector(".button-text").textContent =
          translations[currentLanguage].analyze_face || "Analyze Face"

        if (data.error) {
          console.error("API Error:", data.error)
          alert("Error: " + data.error)
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