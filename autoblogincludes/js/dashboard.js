/**
 * Autoblog Dashboard Chart
 * Uses Chart.js for modern, responsive charts
 */
(function($) {
	'use strict';

	let autoblogChart = null;

	function autoblogDrawChart() {
		let container = document.getElementById('autoblog-dashboard-chart');
		if (!container) return;

		// Ensure we have a canvas element
		let canvas;
		if (container.tagName.toLowerCase() === 'canvas') {
			canvas = container;
		} else {
			// Check if canvas already exists inside container
			canvas = container.querySelector('canvas');
			if (!canvas) {
				// Create canvas element inside the container
				canvas = document.createElement('canvas');
				container.innerHTML = '';
				container.appendChild(canvas);
			}
		}

		const ctx = canvas.getContext('2d');
		if (!ctx) return;

		const today = new Date();
		const stamp = Date.parse(autoblog.date);
		if (!isNaN(stamp)) {
			today.setTime(stamp);
		}

		today.setDate(today.getDate() - 6);

		const labels = [];
		const processedData = [];
		const importsData = [];
		const errorsData = [];

		for (let i = 1; i <= 7; i++) {
			let imports = 0, errors = 0, processed = 0;

			const dateSelector = '#autoblog-log-date-' + today.getFullYear() + '-' + 
				(today.getMonth() + 1) + '-' + today.getDate();
			const date = $(dateSelector);

			date.find('.autoblog-log-feed-imports').each(function() { 
				imports += parseInt($(this).text()) || 0;
			});
			date.find('.autoblog-log-feed-iterations').each(function() { 
				processed += parseInt($(this).text()) || 0;
			});
			date.find('.autoblog-log-feed-errors').each(function() { 
				errors = parseInt($(this).text()) || 0;
			});

			labels.push(today.toLocaleDateString());
			processedData.push(processed);
			importsData.push(imports);
			errorsData.push(errors);

			today.setDate(today.getDate() + 1);
		}

		// Destroy existing chart instance if it exists
		if (autoblogChart) {
			autoblogChart.destroy();
		}

		// Create new chart
		autoblogChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: labels,
				datasets: [
					{
						label: autoblog.processes_column,
						data: processedData,
						backgroundColor: 'rgba(0, 128, 0, 0.9)',
						borderColor: 'rgba(0, 128, 0, 1)',
						borderWidth: 1
					},
					{
						label: autoblog.imports_column,
						data: importsData,
						backgroundColor: 'rgba(1, 177, 243, 0.9)',
						borderColor: 'rgba(1, 177, 243, 1)',
						borderWidth: 1
					},
					{
						label: autoblog.errors_column,
						data: errorsData,
						backgroundColor: 'rgba(255, 0, 0, 0.9)',
						borderColor: 'rgba(255, 0, 0, 1)',
						borderWidth: 1
					}
				]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: {
						position: 'bottom',
						align: 'center'
					}
				},
				scales: {
					y: {
						beginAtZero: true,
						ticks: {
							precision: 0
						}
					}
				}
			}
		});
	}

	$(function() {
		$('.autoblog-log-feed > .autoblog-log-row').on('click', function() {
			const $clickedRow = $(this);
			const $clickedFeed = $clickedRow.parent();
			const $records = $clickedFeed.find('.autoblog-log-feed-records');
			const isOpen = $records.is(':visible');
			
			// Close all other feeds (accordion behavior)
			$('.autoblog-log-feed').not($clickedFeed).each(function() {
				$(this).find('.autoblog-log-feed-collapse-up').hide();
				$(this).find('.autoblog-log-feed-collapse-down').show();
			$(this).find('.autoblog-log-feed-records').slideUp(300);
			});
			
			// Toggle the clicked feed
			if (isOpen) {
				$clickedFeed.find('.autoblog-log-feed-collapse-up').hide();
				$clickedFeed.find('.autoblog-log-feed-collapse-down').show();
			$records.slideUp(300);
			} else {
				$clickedFeed.find('.autoblog-log-feed-collapse-down').hide();
				$clickedFeed.find('.autoblog-log-feed-collapse-up').show();
				
				const rows = $records.find('.autoblog-log-record');
				if (rows.length > 0) {
					const rowHeight = $(rows[0]).outerHeight();
					const totalHeight = rows.length * rowHeight;
					// Show up to 10 rows, max 500px
					const maxHeight = Math.min(totalHeight, rowHeight * 10, 500);
					$records.css('max-height', maxHeight + 'px');
				}

				$records.slideDown(300);
			}

			// Safely redraw chart if it exists
			try {
				if (typeof Chart !== 'undefined' && document.getElementById('autoblog-dashboard-chart')) {
					autoblogDrawChart();
				}
			} catch (e) {
				console.error('Chart redraw error:', e);
			}

			return false;
		});

		// Initialize: hide all records and show down arrows
		$('.autoblog-log-feed-records').hide();
		$('.autoblog-log-feed-collapse-up').hide();
		$('.autoblog-log-feed-collapse-down').show();

		// Initialize chart on load
		if (typeof Chart !== 'undefined') {
			autoblogDrawChart();
		}

		// Redraw chart on window resize
		$(window).on('resize', function() {
			if (autoblogChart) {
				autoblogChart.resize();
			}
		});
	});
})(jQuery);