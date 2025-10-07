<?php
xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                let response = JSON.parse(xhr.responseText);
                showToast(response.message, response.status);
                if (response.status === "success") {
                    document.getElementById("uploadForm").reset();
                    document.getElementById("progressBar").style.width = "0%";
                }
            } catch (e) {
                showToast("⚠️ Invalid server response", "error");
            }
        } else {
            showToast("❌ Upload failed.", "error");
        }
    };

    xhr.onerror = function() {
        showToast("⚠️ Network error during upload.", "error");
    };

    xhr.send(formData);
});