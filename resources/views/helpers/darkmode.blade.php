<script>
  // ===== Dark Mode =====
  function toggleDark() {
    const root = document.documentElement;
    const isDark = root.classList.toggle('dark');
    root.style.colorScheme = isDark ? 'dark' : 'light';
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    if (window.statsChart) updateChartTheme();
  }

  // Default page is pure white (#FFFFFF / light mode first) per Don Norman & Zander Whitehurst guidelines
  const savedTheme = localStorage.getItem('theme');
  const shouldUseDark = savedTheme === 'dark';
  if (shouldUseDark) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
  document.documentElement.style.colorScheme = shouldUseDark ? 'dark' : 'light';
</script>
