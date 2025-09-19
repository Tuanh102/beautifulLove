

<div class="container-fluid p-4">
    <h2 class="bg-dark text-white p-3 rounded mb-4">Cài đặt</h2>

    <div class="card mb-4 shadow">
        <div class="card-body">
            <h5 class="card-title">Chế độ màn hình</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="darkModeSwitch">
                <label class="form-check-label" for="darkModeSwitch">Chuyển sang chế độ tối</label>
            </div>
        </div>
    </div>
</div>

<script>
    // Xử lý chuyển đổi Chế độ tối
    const darkModeSwitch = document.getElementById('darkModeSwitch');
    const body = document.body;

    // Kiểm tra trạng thái hiện tại
    if (localStorage.getItem('theme') === 'dark') {
        darkModeSwitch.checked = true;
    }

    darkModeSwitch.addEventListener('change', () => {
        if (darkModeSwitch.checked) {
            body.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
        } else {
            body.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
        }
    });
</script>