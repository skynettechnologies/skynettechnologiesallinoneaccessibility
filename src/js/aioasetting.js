console.log('seeeting file call');
const domain = document.getElementById('domain').value;
// Fetch the username and user email from the DOM
const usernameElement = document.getElementById('username');
const useremailElement = document.getElementById('email');
// Ensure both elements exist before accessing their innerHTML
const username = usernameElement ? usernameElement.value : 'Username not found';
const useremail = useremailElement ? useremailElement.value : 'Email not found';

console.log(domain, username, useremail);
website_name = btoa(domain);

function fetchApiData(website_name) {
    var packageType = "free-widget";
    var arrDetails = {
        'name': username,
        'email': useremail,
        'company_name': '',
        'website': website_name,
        'package_type': packageType,
        'start_date': new Date().toISOString(),
        'end_date': '',
        'price': '',
        'discount_price': '0',
        'platform': 'Redaxocms',
        'api_key': '',
        'is_trial_period': '',
        'is_free_widget': '1',
        'bill_address': '',
        'country': '',
        'state': '',
        'city': '',
        'post_code': '',
        'transaction_id': '',
        'subscr_id': '',
        'payment_source': ''
    };

    console.log('Details to send:', arrDetails);

    const apiUrl = "https://ada.skynettechnologies.us/api/get-autologin-link-new";
    console.log("website url"+website_name);
    // Prepare the POST request
    fetch(apiUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/json" // Specify the content type
        },
        body: JSON.stringify({ website: website_name }) // Pass the encoded domain name in the request body
    })
        .then(response => {
            // Check if the response is okay (status code 200)
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json(); // Parse the JSON response
        })
        .then(result => {
            // Log the result to check the response structure
            console.log(result); // This will log the full response from the API

            // Check if the response contains a valid link
            if (result && result.link) {
                console.log("Autologin Link:", result.link);  // Log the link
            } else {
                console.error("Invalid response or missing link.");
                const secondApiUrl = "https://ada.skynettechnologies.us/api/add-user-domain";
                // Send the details to the second API
                fetch(secondApiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json" // Specify the content type
                    },
                    body: JSON.stringify(arrDetails) // Pass the array data to the second API
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Response from add-user-domain API:", data);
                        // Handle the response from the add-user-domain API (success/failure)
                        if (data.success) {
                            console.log("User domain added successfully!");
                        } else {
                            console.error("Error adding user domain.");
                        }
                    })
                    .catch(error => {
                        console.error("Error sending data to add-user-domain API:", error);
                    })
                    .finally(() => {
                        // Hide loader after fetching data is complete (success or error)
                        hideLoader();
                    });
            }
        })
        .catch(error => {
            console.error("Error fetching API:", error); // Log any errors
        });
}

var domain_name = domain;
console.log("domain : "+domain);
const defaultSettings = {
    widget_position: "bottom_right",
    widget_color_code: "#422083",
    widget_icon_type: "aioa-icon-type-1",
    widget_icon_size: "aioa-small-icon",
};
var domain_name = domain;
var website_name = btoa(domain_name);
fetchApiResponse(domain_name);
document.addEventListener('DOMContentLoaded', function() {
    website_name = btoa(domain_name);
    fetchApiData(website_name);
    fetchApiResponse(domain_name);
});
function fetchApiResponse(domain_name) {
    const apiUrl = "https://ada.skynettechnologies.us/api/widget-settings";

    fetch(apiUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/json" // Specify the content type
        },
        body: JSON.stringify({ website_url: domain_name }) // Pass the domain name in the request body
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json(); // Parse the JSON response
        })
        .then((result) => {
            // Check if result and result.Data are valid
            if (result && result.Data && Object.keys(result.Data).length > 0) {
                console.log(result.Data);
                const settings = {
                    widget_position: result.Data.widget_position || defaultSettings.widget_position,
                    widget_color_code: result.Data.widget_color_code || defaultSettings.widget_color_code,
                    widget_icon_type: result.Data.widget_icon_type || defaultSettings.widget_icon_type,
                    widget_icon_size: result.Data.widget_icon_size || defaultSettings.widget_icon_size,
                    widget_size: result.Data.widget_size || defaultSettings.widget_size,
                    widget_icon_size_custom: result.Data.widget_icon_size_custom || defaultSettings.widget_icon_size_custom,
                    is_widget_custom_size: result.Data.is_widget_custom_size || defaultSettings.is_widget_custom_size,
                    is_widget_custom_position: result.Data.is_widget_custom_position || defaultSettings.is_widget_custom_position,
                    widget_position_top: result.Data.widget_position_top || 0,
                    widget_position_bottom: result.Data.widget_position_bottom || 0,
                    widget_position_left: result.Data.widget_position_left || 0,
                    widget_position_right: result.Data.widget_position_right || 0,
                };

                populateSettings(settings);
                populatecustom(settings);
                // You can process the settings here or pass them to another function
            } else {
            }
        })
        .catch(error => {
            console.error("Error fetching API:", error);
            // Handle error scenarios like invalid response or network issues
        });
}
function fetchSettings() {
    const requestOptions = {
        method: "POST",
        redirect: "follow"
    };

    fetch(`https://ada.skynettechnologies.us/api/widget-settings?website_url=${domain_name}`, requestOptions)
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json(); // Parse JSON response
        })
        .then((result) => {
            // Check if result and result.Data are valid
            if (result && result.Data && Object.keys(result.Data).length > 0) {
                console.log("Widget settings fetched:", result.Data);
            } else {

            }
        })
        .catch((error) => {
            console.error("Error fetching widget settings:", error);
            alert("Failed to fetch settings. Using default values.");

        })
        .finally(() => {
            // Hide loader after fetching data is complete (success or error)
            hideLoader();
        });


}
// Populate form fields with settings
function populateSettings(settings) {
    if (settings.is_widget_custom_size === 1) {
        $("#custom-size-switcher").prop("checked", true); // Check the checkbox
        $(".custom-size-controls").removeClass("hide"); // Show custom size controls
        $(".widget-icon").addClass("hide"); // Hide widget icon
        $(".custom-size-switcher").closest(".custom-checkbox").addClass("selected"); // Add 'selected' class
    } else {
        $("#custom-size-switcher").prop("checked", false); // Uncheck the checkbox
        $(".custom-size-controls").addClass("hide"); // Hide custom size controls
        $(".widget-icon").removeClass("hide"); // Show widget icon
        $(".custom-size-switcher").closest(".custom-checkbox").removeClass("selected"); // Remove 'selected' class
    }
// Toggle behavior for #custom-size-switcher
    $("#custom-size-switcher").click(function () {
        settings.is_widget_custom_size = $(this).is(":checked") ? 1 : 0; // Update the value

        if (settings.is_widget_custom_size === 1) {
            $(".custom-size-controls").removeClass("hide");
            $(".widget-icon").addClass("hide");
            $(this).closest(".custom-checkbox").addClass("selected");
        } else {
            console.log("settings2: " + settings.is_widget_custom_size);
            $(".custom-size-controls").addClass("hide");
            $(".widget-icon").removeClass("hide");
            $(this).closest(".custom-checkbox").removeClass("selected");
        }
    });
// Simulated API update after fetching settings
    setTimeout(() => {
        $("#custom-size-switcher").prop("checked", settings.is_widget_custom_size === 1);

        if (settings.is_widget_custom_size === 1) {
            $(".custom-size-controls").removeClass("hide");
            $(".widget-icon").addClass("hide");
            $("#custom-size-switcher").closest(".custom-checkbox").addClass("selected");
        } else {
            console.log("settings3: " + settings.is_widget_custom_size);
            $(".custom-size-controls").addClass("hide");
            $(".widget-icon").removeClass("hide");
            $("#custom-size-switcher").closest(".custom-checkbox").removeClass("selected");
        }
    }, 1000);
// Custom Position Switcher
    if (settings.is_widget_custom_position === 1) {
        $("#custom-position-switcher").prop("checked", true); // Check the checkbox
        $(".custom-position-controls").removeClass("hide"); // Show position controls
        $(".widget-position").addClass("hide"); // Hide widget position
        $(".custom-position-switcher").closest(".custom-checkbox").addClass("selected"); // Add 'selected' class
    } else {
        console.log("settings1: " + settings.is_widget_custom_position);
        $("#custom-position-switcher").prop("checked", false); // Uncheck the checkbox
        $(".custom-position-controls").addClass("hide"); // Hide position controls
        $(".widget-position").removeClass("hide"); // Show widget position
        $(".custom-position-switcher").closest(".custom-checkbox").removeClass("selected"); // Remove 'selected' class
    }
// Toggle behavior for #custom-position-switcher
    $("#custom-position-switcher").click(function () {
        settings.is_widget_custom_position = $(this).is(":checked") ? 1 : 0; // Update the value

        if (settings.is_widget_custom_position === 1) {
            $(".custom-position-controls").removeClass("hide");
            $(".widget-position").addClass("hide");
            $(this).closest(".custom-checkbox").addClass("selected");
        } else {
            console.log("settings2: " + settings.is_widget_custom_position);
            $(".custom-position-controls").addClass("hide");
            $(".widget-position").removeClass("hide");
            $(this).closest(".custom-checkbox").removeClass("selected");
        }
    });
// Simulated API update after fetching settings
    setTimeout(() => {
        $("#custom-position-switcher").prop("checked", settings.is_widget_custom_position === 1);

        if (settings.is_widget_custom_position === 1) {
            $(".custom-position-controls").removeClass("hide");
            $(".widget-position").addClass("hide");
            $("#custom-position-switcher").closest(".custom-checkbox").addClass("selected");
        } else {
            console.log("settings3: " + settings.is_widget_custom_position);
            $(".custom-position-controls").addClass("hide");
            $(".widget-position").removeClass("hide");
            $("#custom-position-switcher").closest(".custom-checkbox").removeClass("selected");
        }
    }, 1000);
// end size position
    const colorField = document.getElementById("colorcode");
    if (colorField) {
        colorField.value = settings.widget_color_code;
    }
    const typeOptions = document.querySelectorAll('input[name="aioa_icon_type"]');

    typeOptions.forEach((option) => {
        if (option.value === settings.widget_icon_type) {
            option.checked = true;
        }
    });

    const sizeOptions = document.querySelectorAll('input[name="aioa_icon_size"]');
    sizeOptions.forEach((option) => {
        if (option.value === settings.widget_icon_size) {
            option.checked = true;
        }
    });

    const iconImg = `/apps/allinoneaccessibility/src/img/${settings.widget_icon_type}.svg`;

    $(".iconimg").attr("src", iconImg);

    const widget_icon_size_custom = document.getElementById("widget_icon_size_custom");

    if (widget_icon_size_custom) {
        widget_icon_size_custom.value = settings.widget_icon_size_custom;
    }
    const positionRadio = document.querySelector(`input[name="position"][value="${settings.widget_position}"]`);
    if (positionRadio) {
        positionRadio.checked = true;
    }
    const widget_size = document.querySelector(`input[name="widget_size"][value="${settings.widget_size}"]`);
    if (widget_size) {
        widget_size.checked = true;
    }

    // Set custom position fields
    const customPositionXField = document.getElementById("custom_position_x_value");

    const xDirectionSelect = $(".custom-position-controls select")[0];

    if (customPositionXField && xDirectionSelect) {
        if (settings.widget_position_right > 0) {
            customPositionXField.value = settings.widget_position_right;
            xDirectionSelect.value = "cust-pos-to-the-right";
        } else if (settings.widget_position_left > 0) {
            customPositionXField.value = settings.widget_position_left;
            xDirectionSelect.value = "cust-pos-to-the-left";
        } else {
            customPositionXField.value = 0;
            xDirectionSelect.value = "cust-pos-to-the-right"; // Default direction
        }
    }


    const customPositionYField = document.getElementById("custom_position_y_value");

    const yDirectionSelect = $(".custom-position-controls select")[1];
    if (customPositionYField && yDirectionSelect) {
        if (settings.widget_position_bottom > 0) {
            customPositionYField.value = settings.widget_position_bottom;
            yDirectionSelect.value = "cust-pos-to-the-lower";
        } else if (settings.widget_position_top > 0) {
            customPositionYField.value = settings.widget_position_top;
            yDirectionSelect.value = "cust-pos-to-the-upper";
        } else {
            customPositionYField.value = 0;
            yDirectionSelect.value = "cust-pos-to-the-lower"; // Default direction
        }
    }

}
// Fetch settings when the page loads
// window.onload = function () {
//     showLoader();
//     fetchSettings();
//     domain_name = domain;
//     website_name = btoa(domain_name);
//     fetchApiResponse(domain_name);
// };
window.onload = function () {
    showLoader();
    fetchSettings();
    domain_name = domain;
    website_name = btoa(domain_name);
    fetchApiResponse(domain_name);
    fetchApiData(website_name);
    Promise.all([fetchApiResponse(domain_name), fetchApiData(website_name)]).then(() => {
        hideLoader(); // Hide the loader once both functions complete
    }).catch(error => {
        // Handle any errors
        console.error("Error during API fetch:", error);
        hideLoader(); // Ensure loader is hidden even in case of an error
    });
};
// Show loader function
function showLoader() {
    var loader = document.getElementById('loader');
    if (loader) {
        loader.style.display = 'flex'; // Show loader
    }
}
// Hide loader function
function hideLoader() {
    var loader = document.getElementById('loader');
    if (loader) {
        loader.style.display = 'none'; // Hide loader
    }
}
const sizeOptions = document.querySelectorAll('input[name="aioa_icon_size"]');
const sizeOptionsImg = document.querySelectorAll('input[name="aioa_icon_size"] + label img');
const typeOptions = document.querySelectorAll('input[name="aioa_icon_type"]');
const positionOptions = document.querySelectorAll('input[name="position"]');
const custSizePreview = document.querySelector(".custom-size-preview img");
const custSizePreviewLabel = document.querySelector(".custom-size-preview .value span");
// Set default value to custom position inputs
var positions = {
    top_left: [20, 20],
    middel_left: [20, 50],
    bottom_center: [50, 20],
    top_center: [50, 20],
    middel_right: [20, 50],
    bottom_right: [20, 20],
    top_right: [20, 20],
    bottom_left: [20, 20],
};
positionOptions.forEach((option) => {
    var ico_position = document.querySelector('input[name="position"]:checked').value;
    document.getElementById("custom_position_x_value").value = positions[ico_position][0];
    document.getElementById("custom_position_y_value").value = positions[ico_position][1];
    option.addEventListener("click", (event) => {
        var ico_position = document.querySelector('input[name="position"]:checked').value;
        document.getElementById("custom_position_x_value").value = positions[ico_position][0];
        document.getElementById("custom_position_y_value").value = positions[ico_position][1];
    });
});
// Set icon on type select
typeOptions.forEach((option) => {
    option.addEventListener("click", (event) => {
        var ico_type = document.querySelector('input[name="aioa_icon_type"]:checked').value;

        sizeOptionsImg.forEach((option2) => {
            option2.setAttribute("src", "/apps/allinoneaccessibility/src/img/" + ico_type + ".svg");
        });
        //custSizePreview.setAttribute("src", "/apps/allinoneaccessibility/src/img/" + ico_type + ".svg");
    });
});
// Set icon on size select
sizeOptions.forEach((option) => {
    var ico_size_value = document
        .querySelector('input[name="aioa_icon_size"]:checked + label img')
        .getAttribute("width");

    // Set default value to custom size input
    const widget_icon_size_custom = document.getElementById("widget_icon_size_custom");
    document.getElementById("widget_icon_size_custom").value = widget_icon_size_custom;


    option.addEventListener("click", (event) => {
        var ico_width = document
            .querySelector('input[name="aioa_icon_size"]:checked + label img')
            .getAttribute("width");
        var ico_height = document
            .querySelector('input[name="aioa_icon_size"]:checked + label img')
            .getAttribute("height");
        //custSizePreview.setAttribute("width", ico_width);
        //custSizePreview.setAttribute("height", ico_height);
        document.getElementById("widget_icon_size_custom").value = ico_width;
        //custSizePreviewLabel.innerHTML = ico_width;
    });
});
// Set icons size on input change
document.getElementById("widget_icon_size_custom").addEventListener("input", function () {
    var ico_size_value = document.getElementById("widget_icon_size_custom").value;
    if (ico_size_value >= 20 && ico_size_value <= 150) {
        //custSizePreview.setAttribute("width", ico_size_value);
        //custSizePreview.setAttribute("height", ico_size_value);
        //custSizePreviewLabel.innerHTML = ico_size_value;
    }

});
$('input[name="position"]').change(function () {
    var icon_position = document.querySelector('input[name="position"]:checked').value;
});

$('input[name="aioa_icon_type"]').change(function () {
    var icon_type = document.querySelector('input[name="aioa_icon_type"]:checked').value;
});
$('input[name="aioa_icon_size"]').change(function () {
    var icon_size = document.querySelector('input[name="aioa_icon_size"]:checked').value;
});

$("#aio-code-switch").click(function () {
    var aio_code_switch = $(this).is(":checked") ? 1 : 0; // Update the value
    if (aio_code_switch === 1) {
        $("#aio_advance_code").removeClass("hide");
        $("#aio_normal_code").addClass("hide");
    } else {
        $("#aio_advance_code").addClass("hide");
        $("#aio_normal_code").removeClass("hide");
    }
});

var colorcode = $("#colorcode").val();
if (colorcode == "") {
    colorcode = "420083";
}
var icon_position = document.querySelector('input[name="position"]:checked').value;
var icon_type = document.querySelector('input[name="aioa_icon_type"]:checked').value;
var icon_size = document.querySelector('input[name="aioa_icon_size"]:checked').value;


$('#license_key,#colorcode').change(function () {
    var license_key = $("#license_key").val();
    var colorcode = $("#colorcode").val();
    //var checkedValue = $('.messageCheckbox:checked').val();
});
$('input[name="position"]').change(function () {
    var icon_position = document.querySelector('input[name="position"]:checked').value;
});

$('input[name="aioa_icon_type"]').change(function () {
    var icon_type = document.querySelector('input[name="aioa_icon_type"]:checked').value;

});
$('input[name="aioa_icon_size"]').change(function () {
    var icon_size = document.querySelector('input[name="aioa_icon_size"]:checked').value;

});
// Set the initial server name and display it
document.addEventListener('DOMContentLoaded', function() {
    var server_name = domain
});
let is_widget_custom_position = 0;
let is_widget_custom_size = 0;

function populatecustom(settings) {
    console.log(settings.is_widget_custom_size);

    // Fetch the settings value for custom position and set the checkbox state
    is_widget_custom_position = settings.is_widget_custom_position !== undefined ? settings.is_widget_custom_position : 0;
    $("#custom-position-switcher").prop("checked", is_widget_custom_position === 1);
    console.log("Initial Custom Position Switcher:", is_widget_custom_position);

    // Fetch the settings value for custom size and set the checkbox state
    is_widget_custom_size = settings.is_widget_custom_size !== undefined ? settings.is_widget_custom_size : 0;
    $("#custom-size-switcher").prop("checked", is_widget_custom_size === 1);
    console.log("Initial Custom Size Switcher:", is_widget_custom_size);

    // Handle click event for custom position switcher
    $("#custom-position-switcher").click(function () {
        // Set value based on checkbox state
        is_widget_custom_position = $(this).is(":checked") ? 1 : 0;
        console.log("Custom Position Switcher:", is_widget_custom_position);
    });

    // Handle click event for custom size switcher
    $("#custom-size-switcher").click(function () {
        // Set value based on checkbox state
        is_widget_custom_size = $(this).is(":checked") ? 1 : 0;
        console.log("Custom Size Switcher:", is_widget_custom_size);
    });
}
$("#aioa_submit").click(function () {

	console.log('f1 call');
	var server_name = domain;
	document.getElementById('loader').style.display = 'flex';
	var colorcode = $("#colorcode").val();
	var icon_position = document.querySelector('input[name="position"]:checked').value;
	var icon_type = document.querySelector('input[name="aioa_icon_type"]:checked').value;
	var icon_size = document.querySelector('input[name="aioa_icon_size"]:checked').value;
	var widget_size = document.querySelector('input[name="widget_size"]:checked').value;
	var widget_icon_size_custom = $("#widget_icon_size_custom").val();
	console.log(widget_icon_size_custom);

	// Validate widget_icon_size_custom range
	if (widget_icon_size_custom < 20 || widget_icon_size_custom > 150) {
		alert("The icon size must be between 20 and 150px.");
		document.getElementById('loader').style.display = 'none'; // Hide the loader if validation fails
		return; // Stop the function from proceeding if the validation fails
	}

	const custom_position_x = $("#custom_position_x_value").val() || 0;
	const custom_position_y = $("#custom_position_y_value").val() || 0;
	const x_position_direction = $(".custom-position-controls select").eq(0).val();
	const y_position_direction = $(".custom-position-controls select").eq(1).val();

	let widget_position_right = null;
	let widget_position_left = null;
	let widget_position_top = null;
	let widget_position_bottom = null;

	if (x_position_direction === "cust-pos-to-the-right") {
		widget_position_right = custom_position_x;
	} else if (x_position_direction === "cust-pos-to-the-left") {
		widget_position_left = custom_position_x;
	}

	if (y_position_direction === "cust-pos-to-the-lower") {
		widget_position_bottom = custom_position_y;
	} else if (y_position_direction === "cust-pos-to-the-upper") {
		widget_position_top = custom_position_y;
	}

	var params = new URLSearchParams({
		u: server_name,
		widget_position: icon_position,
		is_widget_custom_position: is_widget_custom_position,
		is_widget_custom_size: is_widget_custom_size,
		widget_color_code: colorcode,
		widget_icon_type: icon_type,
		widget_icon_size: icon_size,
		widget_size: widget_size,
		widget_icon_size_custom: widget_icon_size_custom,
		widget_position_right: widget_position_right,
		widget_position_left: widget_position_left,
		widget_position_top: widget_position_top,
		widget_position_bottom: widget_position_bottom
	}).toString();

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'https://ada.skynettechnologies.us/api/widget-setting-update-platform', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

	xhr.onload = function () {
		// Hide the loader
		document.getElementById('loader').style.display = 'none';
		if (xhr.status === 200) {
			alert('Settings updated successfully!');
			location.reload();
		} else {
			console.error('Error: ', xhr.status, xhr.statusText);
			alert('Error: Unable to update settings.');
		}
	};
	xhr.onerror = function () {
		document.getElementById('loader').style.display = 'none';
		alert('Request failed. Please check your network connection.');
		console.error('Request error:', xhr);
	};
	xhr.send(params);
});

$('#aio_domain').change(function () {
    const {
        host, hostname, href, origin, pathname, port, protocol, search
    } = window.location;
    window.location.href=origin+pathname+'?domain='+($(this).val());
    /*console.log(host); // "ui.dev"
    console.log(hostname); // "ui"
    console.log(href); // "https://ui.dev/get-current-url-javascript/?comments=false"
    console.log(origin); // "https://ui.dev"
    console.log(pathname); // "/get-current-url-javascript/""
    console.log(port); // ""
    console.log(protocol); // "https:"
    console.log(search); // "?comments=false"*/
});
