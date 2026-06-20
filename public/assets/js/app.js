(() => {
  const app = document.querySelector('.wedora-app');
  const $ = (selector, root = document) => root.querySelector(selector);
  const $$ = (selector, root = document) => Array.from(root.querySelectorAll(selector));

  const postJSON = async (url, payload) => {
    const response = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });
    const data = await response.json();
    if (!response.ok || !data.ok) {
      throw new Error(data.message || 'Something went wrong');
    }
    return data;
  };

  const formPayload = (form) => Object.fromEntries(new FormData(form).entries());

  const setStatus = (node, message, isError = false) => {
    if (!node) return;
    node.textContent = message;
    node.classList.toggle('error', isError);
  };

  const updateSavedButtons = (vendorId, saved) => {
    $$(`[data-save-vendor="${CSS.escape(vendorId)}"]`).forEach((button) => {
      button.classList.toggle('saved', saved);
      button.classList.remove('heart-pop');
      void button.offsetWidth;
      button.classList.add('heart-pop');

      const label = $('span', button);
      if (label) label.textContent = saved ? 'Saved' : 'Save';
      button.setAttribute('aria-label', saved ? 'Remove from mood board' : 'Save to mood board');
    });
  };

  const updateSavedCount = (count) => {
    $$('[data-saved-count]').forEach((node) => {
      node.textContent = String(count);
    });
  };

  document.addEventListener('click', async (event) => {
    const saveButton = event.target.closest('[data-save-vendor]');
    if (!saveButton) return;

    event.preventDefault();
    const vendorId = saveButton.dataset.saveVendor;
    saveButton.disabled = true;

    try {
      const data = await postJSON('api/save.php', { vendor_id: vendorId });
      updateSavedButtons(vendorId, data.saved);
      updateSavedCount(data.count);

      const card = saveButton.closest('[data-vendor-card]');
      if (!data.saved && card && location.pathname.endsWith('/saved.php')) {
        card.remove();
        const countNode = $('[data-results-count]');
        if (countNode) countNode.textContent = String(data.count);
      }
    } catch (error) {
      console.error(error);
    } finally {
      saveButton.disabled = false;
    }
  });

  $('[data-theme-toggle]')?.addEventListener('click', async () => {
    const nextTheme = app?.classList.contains('theme-dark') ? 'light' : 'dark';
    app?.classList.toggle('theme-dark', nextTheme === 'dark');
    app?.setAttribute('data-theme', nextTheme);

    try {
      await postJSON('api/set-theme.php', { theme: nextTheme });
    } catch (error) {
      console.error(error);
    }
  });

  document.addEventListener('click', (event) => {
    const openButton = event.target.closest('[data-modal-open]');
    if (openButton) {
      const modal = document.getElementById(openButton.dataset.modalOpen);
      modal?.classList.remove('hidden');
    }

    if (event.target.matches('[data-modal], [data-modal-close]') || event.target.closest('[data-modal-close]')) {
      event.target.closest('[data-modal]')?.classList.add('hidden');
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      $$('[data-modal]').forEach((modal) => modal.classList.add('hidden'));
    }
  });

  const filterForm = $('[data-filter-form]');
  if (filterForm) {
    const fetchResults = async () => {
      const params = new URLSearchParams(new FormData(filterForm));
      const clean = new URLSearchParams();
      params.forEach((value, key) => {
        if (value) clean.set(key, value);
      });

      const url = `discover.php${clean.toString() ? `?${clean}` : ''}`;
      history.replaceState(null, '', url);

      const response = await fetch(`api/vendors.php?${clean}`);
      const data = await response.json();
      if (!data.ok) return;

      const results = $('[data-results]');
      const count = $('[data-results-count]');
      if (results) results.innerHTML = data.html;
      if (count) count.textContent = String(data.count);
    };

    const syncChipState = (name, value) => {
      $$(`[data-filter-name="${CSS.escape(name)}"]`, filterForm).forEach((chip) => {
        chip.classList.toggle('active', chip.dataset.filterValue === value && value !== '');
      });
    };

    filterForm.addEventListener('click', (event) => {
      const chip = event.target.closest('[data-filter-name]');
      if (!chip) return;

      const name = chip.dataset.filterName;
      const hidden = $(`[data-filter-hidden="${CSS.escape(name)}"]`, filterForm);
      if (!hidden) return;

      hidden.value = chip.classList.contains('active') ? '' : chip.dataset.filterValue;
      syncChipState(name, hidden.value);
      fetchResults().catch(console.error);
    });

    filterForm.addEventListener('submit', (event) => {
      event.preventDefault();
      fetchResults().catch(console.error);
    });

    let searchTimer = 0;
    $('input[name="q"]', filterForm)?.addEventListener('input', () => {
      clearTimeout(searchTimer);
      searchTimer = window.setTimeout(() => fetchResults().catch(console.error), 220);
    });
  }

  $$('[data-enquiry-form]').forEach((form) => {
    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      const status = $('[data-form-status]', form);
      const button = $('button[type="submit"]', form);
      button.disabled = true;
      setStatus(status, 'Sending...');

      try {
        const data = await postJSON('api/enquiry.php', formPayload(form));
        setStatus(status, data.message);
        form.reset();
      } catch (error) {
        setStatus(status, error.message, true);
      } finally {
        button.disabled = false;
      }
    });
  });

  $$('[data-vendor-register-form]').forEach((form) => {
    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      const status = $('[data-form-status]', form);
      const button = $('button[type="submit"]', form);
      button.disabled = true;
      setStatus(status, 'Submitting...');

      try {
        const data = await postJSON('api/vendor-register.php', formPayload(form));
        setStatus(status, data.message);
        form.reset();
      } catch (error) {
        setStatus(status, error.message, true);
      } finally {
        button.disabled = false;
      }
    });
  });

  $('[data-checklist]')?.addEventListener('change', async (event) => {
    const input = event.target.closest('[data-checklist-item]');
    if (!input) return;

    const item = input.closest('.check-item');
    item?.classList.toggle('done', input.checked);

    try {
      const data = await postJSON('api/checklist.php', {
        item_id: input.dataset.checklistItem,
        completed: input.checked,
      });
      const panel = input.closest('.checklist-panel');
      const progressTitle = $('.panel-heading h2', panel);
      const progressRing = $('.progress-ring', panel);
      const progressBar = $('.progress-bar span', panel);

      if (progressTitle) progressTitle.textContent = `${data.progress}% complete`;
      if (progressRing) {
        progressRing.textContent = String(data.progress);
        progressRing.style.setProperty('--progress', data.progress);
      }
      if (progressBar) progressBar.style.width = `${data.progress}%`;
    } catch (error) {
      input.checked = !input.checked;
      item?.classList.toggle('done', input.checked);
      console.error(error);
    }
  });

  const chatForm = $('[data-chat-form]');
  if (chatForm) {
    const chatWindow = $('[data-chat-window]');
    const chatStatus = $('[data-chat-status]');
    const appendBubble = (role, content) => {
      const bubble = document.createElement('div');
      bubble.className = `chat-bubble ${role}`;
      bubble.textContent = content;
      chatWindow?.appendChild(bubble);
      if (chatWindow) chatWindow.scrollTop = chatWindow.scrollHeight;
    };

    if (chatWindow) chatWindow.scrollTop = chatWindow.scrollHeight;

    chatForm.addEventListener('submit', async (event) => {
      event.preventDefault();
      const input = $('input[name="message"]', chatForm);
      const message = input?.value.trim();
      if (!message) return;

      appendBubble('user', message);
      input.value = '';
      setStatus(chatStatus, 'Thinking...');

      try {
        const data = await postJSON('api/concierge.php', { message });
        appendBubble('assistant', data.reply);
        setStatus(chatStatus, '');
      } catch (error) {
        setStatus(chatStatus, error.message, true);
      }
    });
  }
})();
