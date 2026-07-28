<script>
  // ===== Dark Mode =====
  function toggleDark() {
    const root = document.documentElement;
    const isDark = root.classList.toggle('dark');
    root.style.colorScheme = isDark ? 'dark' : 'light';
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    if (window.statsChart) updateChartTheme();
  }

  const savedTheme = localStorage.getItem('theme');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const shouldUseDark = savedTheme === 'dark' || (!savedTheme && prefersDark);
  if (shouldUseDark) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
  document.documentElement.style.colorScheme = shouldUseDark ? 'dark' : 'light';
</script>
