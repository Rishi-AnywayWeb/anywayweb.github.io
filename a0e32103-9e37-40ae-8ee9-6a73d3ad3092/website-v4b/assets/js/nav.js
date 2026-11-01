// Mobile menu: full-screen overlay. Closes on any link tap, Escape, or when resized to desktop.
(function(){
  var nav = document.getElementById('mainNav');
  if (!nav || !window.bootstrap) return;
  function close(){ var inst = bootstrap.Collapse.getInstance(nav); if (inst) inst.hide(); }
  nav.addEventListener('show.bs.collapse', function(){ window.scrollTo(0, 0); document.body.classList.add('menu-open'); });
  nav.addEventListener('hidden.bs.collapse', function(){ document.body.classList.remove('menu-open'); });
  nav.querySelectorAll('a').forEach(function(a){ a.addEventListener('click', close); });
  document.addEventListener('keydown', function(e){ if (e.key === 'Escape') close(); });
  window.addEventListener('resize', function(){ if (window.innerWidth >= 992) { document.body.classList.remove('menu-open'); } });
})();
