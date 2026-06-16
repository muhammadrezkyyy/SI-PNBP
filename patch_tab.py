import os

dashboard_path = r"d:\laragon\www\SI-PNBP\resources\views\livewire\admin\audit-dashboard.blade.php"
with open(dashboard_path, "r", encoding="utf-8") as f:
    content = f.read()

# Add JS for Tab interception
js_code = """
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && e.target.classList.contains('bpn-editable')) {
                e.preventDefault();
                // Insert 4 non-breaking spaces for a reliable Tab in both HTML and PDF
                document.execCommand('insertHTML', false, '&nbsp;&nbsp;&nbsp;&nbsp;');
            }
        });
    </script>
</div>
"""
if "document.addEventListener('keydown'" not in content:
    # Find the last </div> before the end of the file or just replace the last </div>
    content = content.replace("</div>\n</div>\n\n", "</div>\n" + js_code + "\n</div>\n\n")

with open(dashboard_path, "w", encoding="utf-8") as f:
    f.write(content)

print("Patch applied for Tab support.")
