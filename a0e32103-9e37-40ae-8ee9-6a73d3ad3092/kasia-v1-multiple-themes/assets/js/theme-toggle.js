(function(){
  var LINK_ID = 'themeStylesheet';
  var STORAGE_KEY = 'kasia-theme';
  function applyTheme(theme){
    var link = document.getElementById(LINK_ID);
    if (link) link.setAttribute('href', '../assets/css/' + theme + '.css');
    document.querySelectorAll('.theme-toggle-btn').forEach(function(btn){
      btn.classList.toggle('active', btn.getAttribute('data-theme') === theme);
    });
  }
  var saved = localStorage.getItem(STORAGE_KEY) || 'theme01';
  applyTheme(saved);
  document.addEventListener('DOMContentLoaded', function(){
    applyTheme(saved);
    document.querySelectorAll('.theme-toggle-btn').forEach(function(btn){
      btn.addEventListener('click', function(){
        var theme = btn.getAttribute('data-theme');
        localStorage.setItem(STORAGE_KEY, theme);
        applyTheme(theme);
      });
    });
  });
})();