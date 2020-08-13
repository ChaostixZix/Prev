(function($){
  "use strict";
var path = window.location.pathname.split('/');
/*
 const updateProperties = (elem, state) => {
  elem.style.setProperty('--x', `${state.x}px`)
  elem.style.setProperty('--y', `${state.y}px`)
  elem.style.setProperty('--width', `${state.width}px`)
  elem.style.setProperty('--height', `${state.height}px`)
  elem.style.setProperty('--radius', state.radius)
  elem.style.setProperty('--scale', state.scale)
}

document.querySelectorAll('.cursor').forEach(cursor => {
  let onElement

  const createState = e => {
    const defaultState = {
      x: e.clientX,
      y: e.clientY,
      width: 10,
      height: 10,
      radius: '50%'
    }

    const computedState = {}

    if (onElement != null) {
      const { top, left, width, height } = onElement.getBoundingClientRect()
      const radius = "50%"
      computedState.x = left + width / 2
      computedState.y = top + height / 2
      computedState.width = 25
      computedState.height = 25
      computedState.radius = radius
    }

    return {
      ...defaultState,
      ...computedState
    }
  }

  document.addEventListener('mousemove', e => {
    const state = createState(e)
    updateProperties(cursor, state)
  })

  document.querySelectorAll('a, button').forEach(elem => {
    elem.addEventListener('mouseenter', () => (onElement = elem))
    elem.addEventListener('mouseleave', () => (onElement = undefined))
  })
});*/

if (path[2] === undefined || path[2] === "") {
  $('.profile-block-card:first').toggleClass('active');
  $('.bottom-bar li:first').toggleClass('active');
}
$(".profile-block-card").each(function() {
    if ($(this).find('a').attr('href') == window.location.href) {
        $(this).addClass("active");
    }
});

$(".bottom-bar li").each(function() {
    if ($(this).find('a').attr('route') == window.location.href) {
        $(this).addClass("active");
    }
});
if (sessionStorage['background'] == 'dark') {
   $('body').addClass('background-dark');
   $('.dark-mode').addClass('on');
   $('.dark-mode').find('em').removeClass('ni-moon');
   $('.dark-mode').find('em').addClass('ni-sun');
}
$(document).on('click', '.dark-mode', function() {

  if ($('body').hasClass('background-dark')) {
      $(this).find('em').removeClass('ni-sun');
      $(this).find('em').addClass('ni-moon');
  } else {
      $(this).find('em').removeClass('ni-moon');
      $(this).find('em').addClass('ni-sun');
  }
  if ($('body').hasClass('background-dark')) {
    sessionStorage.setItem('background', 'light');
    $('body').removeClass('background-dark');
    $(this).removeClass('on');
    $('body').addClass('theme-background');
  }else{
    sessionStorage.setItem('background', 'dark');
    $('body').addClass('background-dark');
    $('body').removeClass('theme-background');
    $(this).addClass('on');
  }
  return false;
});
})(jQuery);