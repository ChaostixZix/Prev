(function($){
  "use strict";
const redirect = (url, full = false) => {
    let base_url = $('#url').val();
    window.location.href = full ? url : `${base_url}${url}`;
};

$('#update_trans').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var key   = button.data('key');
  var value = button.data('value');
  var previous_key = button.data('previous_key');
  var modal = $(this);
  modal.find('.modal-body input[name="key"]').val(key);
  modal.find('.modal-body input[name="previous_key"]').val(previous_key);
  modal.find('.modal-body input[name="value"]').val(value);
});
$('[data-search]').on('keyup', function () {
  var searchVal = $(this).val();
  var filterItems = $('[data-filter-item]');
  if (searchVal != '') {
    filterItems.addClass('d-none');
    $('[data-filter-item][data-filter-name*="' + searchVal.toLowerCase() + '"]').removeClass('d-none');
  } else {
    filterItems.removeClass('d-none');
  }
});
$(document).on('click', '.redirect-href', function(){
  redirect($(this).attr('href'), true);
});

$('[role="iconpicker"]').on('change', event => {
    $(event.currentTarget).closest('.link-icon').find('input').attr('value', event.icon).trigger('change');
});

$(document).ready(function() {
  $('[auto-submit]').submit();
  let current_background_type = $('[name=background_type]').find(':selected').val();
  $('.background-type.' + current_background_type).show();
  let current_linkcolor_type = $('[name=link_row_color_type]').find(':selected').val();
  $('.background-links-type.' + current_linkcolor_type).show();
      // for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        // save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', $(this).attr('href'));
    });
    // go to the latest tab, if it exists:
    var lastTab = localStorage.getItem('lastTab');
    if (lastTab) {
        $('[href="' + lastTab + '"]').tab('show');
    }
});
$(document).on('change', '[name=background_type]', function() {
  let background_type = $(this).val();
   if(background_type === background_type) {
      $('.background-type').fadeOut();
      $('.background-type.' + background_type).fadeIn();
  }
});
$(document).on('change', '[name=link_row_color_type]', function() {

  let background_type = $(this).val();
   if(background_type === background_type) {
      $('.background-links-type').fadeOut();
      $('.background-links-type.' + background_type).fadeIn();
  }
});
$(document).on('click', '.inner-templates', function(){
    var $this       = $(this);
    var $closest    = $this.closest('.inner-templates');
    var $template      = $this.data('template');
    $('.inner-templates').removeClass('active');
    $this.addClass('active');
    $('select[name=settings_template] option').removeAttr('selected','selected');
    $('select[name=settings_template] option[data-value='+$template+']').attr('selected','selected');
});
$(document).on('click', '.color', function(){
    var $this = $(this);
    var $closest = $this.closest('.colors');
    var $color = $this.data('color');
    $('.color').removeClass('active');
    $this.addClass('active');
    $closest.find('input[name=background_gradient]').val($color);
});
$(window).scroll(function() {
    var scroll = $(window).scrollTop();

    if (scroll >= 50) {
        $(".sticky").addClass("nav-sticky");
    } else {
        $(".sticky").removeClass("nav-sticky");
    }
});
/* Confirm delete handler */
$('body').on('click', '[data-confirm]', (event) => {
    let message = $(event.currentTarget).attr('data-confirm');

    if(!confirm(message)) return false;
});

// SmoothLink
$('.smoothlink').on('click', function(event) {
    var $anchor = $(this);
    $('html, body').stop().animate({
        scrollTop: $($anchor.attr('href')).offset().top - 0
    }, 1500, 'easeInOutExpo');
    event.preventDefault();
});

$(document).on('click', '.submit-closest', function() { var $this = $(this); var $form = $this.closest('#form-submit'); $form.submit(); });


    function analyticsDoughnut(selector, set_data) {
        var $selector = $(selector || ".analytics-doughnut");
        $selector.each(function() {
            for (var $self = $(this), _self_id = $self.attr("id"), _get_data = void 0 === set_data ? eval(_self_id) : set_data, selectCanvas = document.getElementById(_self_id).getContext("2d"), chart_data = [], i = 0; i < _get_data.datasets.length; i++) chart_data.push({
                backgroundColor: _get_data.datasets[i].background,
                borderWidth: 2,
                borderColor: _get_data.datasets[i].borderColor,
                hoverBorderColor: _get_data.datasets[i].borderColor,
                data: _get_data.datasets[i].data
            });
            var chart = new Chart(selectCanvas, {
                type: "doughnut",
                data: {
                    labels: _get_data.labels,
                    datasets: chart_data
                },
                options: {
                    legend: {
                        display: !!_get_data.legend && _get_data.legend,
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            fontColor: "#6783b8"
                        }
                    },
                    rotation: -1.5,
                    cutoutPercentage: 70,
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: !0,
                        callbacks: {
                            title: function(a, e) {
                                return e.labels[a[0].index]
                            },
                            label: function(a, e) {
                                return e.datasets[a.datasetIndex].data[a.index] + " " + _get_data.dataUnit
                            }
                        },
                        backgroundColor: "#fff",
                        borderColor: "#eff6ff",
                        borderWidth: 2,
                        titleFontSize: 13,
                        titleFontColor: "#6783b8",
                        titleMarginBottom: 6,
                        bodyFontColor: "#9eaecf",
                        bodyFontSize: 12,
                        bodySpacing: 4,
                        yPadding: 10,
                        xPadding: 10,
                        footerMarginTop: 0,
                        displayColors: !1
                    }
                }
            })
        })
    }
    function orderOverviewChart(selector, set_data) {
        var $selector = $(selector || ".order-overview-chart");
        $selector.each(function () {
            for (
                var $self = $(this),
                    _self_id = $self.attr("id"),
                    _get_data = void 0 === set_data ? eval(_self_id) : set_data,
                    _d_legend = void 0 !== _get_data.legend && _get_data.legend,
                    selectCanvas = document.getElementById(_self_id).getContext("2d"),
                    chart_data = [],
                    i = 0;
                i < _get_data.datasets.length;
                i++
            )
                chart_data.push({
                    label: _get_data.datasets[i].label,
                    data: _get_data.datasets[i].data,
                    backgroundColor: _get_data.datasets[i].color,
                    borderWidth: 2,
                    borderColor: "transparent",
                    hoverBorderColor: "transparent",
                    borderSkipped: "bottom",
                    barPercentage: 0.8,
                    categoryPercentage: 0.6,
                });
            var chart = new Chart(selectCanvas, {
                type: "bar",
                data: { labels: _get_data.labels, datasets: chart_data },
                options: {
                    legend: { display: !!_get_data.legend && _get_data.legend, labels: { boxWidth: 30, padding: 20, fontColor: "#6783b8" } },
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: !0,
                        callbacks: {
                            title: function (e, a) {
                                return a.datasets[e[0].datasetIndex].label;
                            },
                            label: function (e, a) {
                                return a.datasets[e.datasetIndex].data[e.index] + " " + _get_data.dataUnit;
                            },
                        },
                        backgroundColor: "#eff6ff",
                        titleFontSize: 13,
                        titleFontColor: "#6783b8",
                        titleMarginBottom: 6,
                        bodyFontColor: "#9eaecf",
                        bodyFontSize: 12,
                        bodySpacing: 4,
                        yPadding: 10,
                        xPadding: 10,
                        footerMarginTop: 0,
                        displayColors: !1,
                    },
                    scales: {
                        yAxes: [
                            {
                                display: !0,
                                stacked: !!_get_data.stacked && _get_data.stacked,
                                ticks: {
                                    beginAtZero: !0,
                                    fontSize: 11,
                                    fontColor: "#9eaecf",
                                    padding: 10,
                                },
                                gridLines: { color: "#e5ecf8", tickMarkLength: 0, zeroLineColor: "#e5ecf8" },
                            },
                        ],
                        xAxes: [
                            {
                                display: !0,
                                stacked: !!_get_data.stacked && _get_data.stacked,
                                ticks: { fontSize: 9, fontColor: "#9eaecf", source: "auto", padding: 10 },
                                gridLines: { color: "transparent", tickMarkLength: 0, zeroLineColor: "transparent" },
                            },
                        ],
                    },
                },
            });
        });
    }
    orderOverviewChart();
    function lineChart(selector, set_data) {
        var $selector = $(selector || ".line-chart");
        $selector.each(function() {
            for (var $self = $(this), _self_id = $self.attr("id"), _get_data = void 0 === set_data ? eval(_self_id) : set_data, selectCanvas = document.getElementById(_self_id).getContext("2d"), chart_data = [], i = 0; i < _get_data.datasets.length; i++) chart_data.push({
                label: _get_data.datasets[i].label,
                tension: _get_data.lineTension,
                backgroundColor: _get_data.datasets[i].background,
                borderWidth: 3,
                borderColor: _get_data.datasets[i].color,
                pointBorderColor: _get_data.datasets[i].color,
                pointBackgroundColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: _get_data.datasets[i].color,
                pointBorderWidth: 3,
                pointHoverRadius: 4,
                pointHoverBorderWidth: 2,
                pointRadius: 4,
                pointHitRadius: 4,
                data: _get_data.datasets[i].data
            });
            var chart = new Chart(selectCanvas, {
                type: "line",
                data: {
                    labels: _get_data.labels,
                    datasets: chart_data
                },
                options: {
                    legend: {
                        display: !!_get_data.legend && _get_data.legend,
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            fontColor: "#6783b8"
                        }
                    },
                    maintainAspectRatio: !1,
                    tooltips: {
                        enabled: !0,
                        callbacks: {
                            title: function(a, t) {
                                return t.labels[a[0].index]
                            },
                            label: function(a, t) {
                                return t.datasets[a.datasetIndex].data[a.index] + " " + _get_data.dataUnit
                            }
                        },
                        backgroundColor: "#eff6ff",
                        titleFontSize: 13,
                        titleFontColor: "#6783b8",
                        titleMarginBottom: 6,
                        bodyFontColor: "#9eaecf",
                        bodyFontSize: 12,
                        bodySpacing: 4,
                        yPadding: 10,
                        xPadding: 10,
                        footerMarginTop: 0,
                        displayColors: !1
                    },
                    scales: {
                        yAxes: [{
                            display: !0,
                            ticks: {
                                beginAtZero: !1,
                                fontSize: 12,
                                fontColor: "#9eaecf",
                                padding: 10
                            },
                            gridLines: {
                                color: "#e5ecf8",
                                tickMarkLength: 0,
                                zeroLineColor: "#e5ecf8"
                            }
                        }],
                        xAxes: [{
                            display: !0,
                            ticks: {
                                fontSize: 12,
                                fontColor: "#9eaecf",
                                source: "auto",
                                padding: 5
                            },
                            gridLines: {
                                color: "transparent",
                                tickMarkLength: 10,
                                zeroLineColor: "#e5ecf8",
                                offsetGridLines: !0
                            }
                        }]
                    }
                }
            })
        })
    }
    lineChart();
function doughnutChart(selector, set_data) {
    var $selector = $(selector || ".doughnut-chart");
    $selector.each(function() {
        for (var $self = $(this), _self_id = $self.attr("id"), _get_data = void 0 === set_data ? eval(_self_id) : set_data, selectCanvas = document.getElementById(_self_id).getContext("2d"), chart_data = [], i = 0; i < _get_data.datasets.length; i++) chart_data.push({
            backgroundColor: _get_data.datasets[i].background,
            borderWidth: 2,
            borderColor: _get_data.datasets[i].borderColor,
            hoverBorderColor: _get_data.datasets[i].borderColor,
            data: _get_data.datasets[i].data
        });
        var chart = new Chart(selectCanvas, {
            type: "doughnut",
            data: {
                labels: _get_data.labels,
                datasets: chart_data
            },
            options: {
                legend: {
                    display: !!_get_data.legend && _get_data.legend,
                    labels: {
                        boxWidth: 12,
                        padding: 20,
                        fontColor: "#6783b8"
                    }
                },
                rotation: 1,
                cutoutPercentage: 40,
                maintainAspectRatio: !1,
                tooltips: {
                    enabled: !0,
                    callbacks: {
                        title: function(a, t) {
                            return t.labels[a[0].index]
                        },
                        label: function(a, t) {
                            return t.datasets[a.datasetIndex].data[a.index]
                        }
                    },
                    backgroundColor: "#eff6ff",
                    titleFontSize: 13,
                    titleFontColor: "#6783b8",
                    titleMarginBottom: 6,
                    bodyFontColor: "#9eaecf",
                    bodyFontSize: 12,
                    bodySpacing: 4,
                    yPadding: 10,
                    xPadding: 10,
                    footerMarginTop: 0,
                    displayColors: !1
                }
            }
        })
    })
}
doughnutChart();


!function (NioApp, $) {
  "use strict"; // DataTable Init
  function jqvmap_init() {
    var elm = '.vector-map';

    if ($(elm).exists() && typeof $.fn.vectorMap === 'function') {
      $(elm).each(function () {
        var $self = $(this),
            _self_id = $self.attr('id'),
            map_data = eval(_self_id);

        $self.vectorMap({
          map: map_data.map,
          backgroundColor: 'transparent',
          borderColor: '#dee6ed',
          borderOpacity: 1,
          borderWidth: 1,
          color: '#ccd7e2',
          enableZoom: false,
          hoverColor: '#9cabff',
          hoverOpacity: null,
          normalizeFunction: 'polynomial',
          scaleColors: ['#ccd7e2', '#798bff'],
          selectedColor: '#6576ff',
          showTooltip: true,
          values: map_data.data,
          onLabelShow: function onLabelShow(event, label, code) {
            var mapData = JQVMap.maps,
                what = Object.keys(mapData)[0],
                name = mapData[what].paths[code]['name'];
            label.html(name + ' - ' + (map_data.data[code] || 0));
          }
        });
      });
    }
  }

  ;
  NioApp.coms.docReady.push(jqvmap_init);

  function month_v(selector, set_data) {
    var $selector = selector ? $(selector) : $('.monthly-visits');
    $selector.each(function () {
      var $self = $(this),
          _self_id = $self.attr('id'),
          _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

      var selectCanvas = document.getElementById(_self_id).getContext("2d");
      var chart_data = [];

      for (var i = 0; i < _get_data.datasets.length; i++) {
        chart_data.push({
          label: _get_data.datasets[i].label,
          tension: .4,
          backgroundColor: NioApp.hexRGB(_get_data.datasets[i].color, .3),
          borderWidth: 2,
          borderColor: _get_data.datasets[i].color,
          pointBorderColor: 'transparent',
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: "#fff",
          pointHoverBorderColor: _get_data.datasets[i].color,
          pointBorderWidth: 2,
          pointHoverRadius: 4,
          pointHoverBorderWidth: 2,
          pointRadius: 4,
          pointHitRadius: 4,
          data: _get_data.datasets[i].data
        });
      }

      var chart = new Chart(selectCanvas, {
        type: 'line',
        data: {
          labels: _get_data.labels,
          datasets: chart_data
        },
        options: {
          legend: {
            display: false
          },
          maintainAspectRatio: false,
          tooltips: {
            enabled: true,
            callbacks: {
              title: function title(tooltipItem, data) {
                return false;
              },
              label: function label(tooltipItem, data) {
                return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem['index']] + ' ' + _get_data.dataUnit;
              }
            },
            backgroundColor: '#fff',
            titleFontSize: 11,
            titleFontColor: '#6783b8',
            titleMarginBottom: 4,
            bodyFontColor: '#9eaecf',
            bodyFontSize: 10,
            bodySpacing: 3,
            yPadding: 8,
            xPadding: 8,
            footerMarginTop: 0,
            displayColors: false
          },
          scales: {
            yAxes: [{
              display: false,
              ticks: {
                beginAtZero: true
              }
            }],
            xAxes: [{
              display: false
            }]
          }
        }
      });
    });
  } // init investProfit


  NioApp.coms.docReady.push(function () {
    month_v();
  });
}(NioApp, jQuery);

let text_color_pickr_element = $('#color-type');


let pickr_options = {
    components: {

        // Main components
        preview: true,
        opacity: false,
        hue: true,
        comparison: false,

        // Input / output Options
        interaction: {
            hex: true,
            rgba: true,
            hsla: false,
            hsva: false,
            cmyk: false,
            input: true,
            clear: false,
            save: true
        }
    }
};
if(text_color_pickr_element.length > 0) {
    let $closest = $('.background-type.color');
    let color_input = $closest.find('input[name="background"]');

    // Text Color Handler
    let color_pickr = Pickr.create({
        el: '#color-type',
        default: color_input.val(),
        ...pickr_options
    });
    color_pickr.off().on('change', hsva => {
        color_input.val(hsva.toHEXA().toString()); 
    });
}
let general_color_pickr_element = $('[pickr]');
if(general_color_pickr_element.length > 0) {
    let $pickr = $('[pickr]');
    let $el = $pickr.find('[pickr-div]').attr('id');
    let color_input = $pickr.find('[pickr-input]');
    // Text Color Handler
    var color_pickr = Pickr.create({
        el: "#"+$el,
        default: color_input.val(),
        ...pickr_options
    });
    color_pickr.off().on('change', hsva => {
        color_input.val(hsva.toHEXA().toString()); 
    });
}
let links_color_pickr_element = $('#links_color');
if(links_color_pickr_element.length > 0) {
    let $closest = $('.background');
    let color_input = $closest.find('input[name="link_row_background"]');
    // Text Color Handler
    let color_pickr = Pickr.create({
        el: '#links_color',
        default: color_input.val(),
        ...pickr_options
    });
    color_pickr.off().on('change', hsva => {
        color_input.val(hsva.toHEXA().toString()); 
    });

}
let linkstext_color_pickr_element = $('#links_text_color');
if(linkstext_color_pickr_element.length > 0) {
    let $closest = $('.background');
    let color_input = $closest.find('input[name="link_row_textcolor"]');
    // Text Color Handler
    let color_pickr = Pickr.create({
        el: '#links_text_color',
        default: color_input.val(),
        ...pickr_options
    });
    color_pickr.off().on('change', hsva => {
        color_input.val(hsva.toHEXA().toString()); 
    });

}


$(".avatar_custom").change(function() {
  var input = this;
  var $this = $(this);
  var $parent = $this.closest('.profile-avatar');
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
        $parent.find('img').attr("src", ""+e.target.result+"");
        $parent.addClass('active');
        $parent.find('label').text('You' + "'ve" + ' selected an image');
    }
    reader.readAsDataURL(input.files[0]);
  }
});
$(".upload").change(function() {
  var input = this;
  var $this = $(this);
  var $parent = $this.closest('.image-upload');
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
        $parent.find('img').attr("src", ""+e.target.result+"");
        $parent.addClass('active');
        $parent.find('label').text('You' + "'ve" + ' selected an image');
    }
    reader.readAsDataURL(input.files[0]);
  }
});

 $("iframe#site").height($(document).height()-$("#frame").height());
 $(document).on('click', '.nav-item', function(e){
     $('.nav-link').removeClass('active');
     $(this).find('.nav-link').addClass('active');
   });
})(jQuery);