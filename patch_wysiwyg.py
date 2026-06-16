import os
import re

dashboard_path = r"d:\laragon\www\SI-PNBP\resources\views\livewire\admin\audit-dashboard.blade.php"
with open(dashboard_path, "r", encoding="utf-8") as f:
    content = f.read()

# 1. Add the toolbar right before <div class="bg-white text-black p-6 sm:p-8 shadow-sm relative group border border-slate-200 mx-auto"
toolbar_html = """                {{-- Toolbar MS Word Style --}}
                <div class="max-w-[794px] mx-auto bg-white border border-slate-300 shadow-sm mb-4 rounded flex items-center p-2 gap-1 sticky top-0 z-50">
                    <button type="button" onclick="document.execCommand('bold', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded font-bold text-black border border-transparent hover:border-slate-300" title="Bold (Ctrl+B)">B</button>
                    <button type="button" onclick="document.execCommand('italic', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded italic text-black border border-transparent hover:border-slate-300" title="Italic (Ctrl+I)">I</button>
                    <button type="button" onclick="document.execCommand('underline', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded underline text-black border border-transparent hover:border-slate-300" title="Underline (Ctrl+U)">U</button>
                    <div class="w-px h-6 bg-slate-300 mx-1"></div>
                    <button type="button" onclick="document.execCommand('justifyLeft', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded text-black border border-transparent hover:border-slate-300" title="Align Left">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h16" /></svg>
                    </button>
                    <button type="button" onclick="document.execCommand('justifyCenter', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded text-black border border-transparent hover:border-slate-300" title="Align Center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M7 12h10M4 18h16" /></svg>
                    </button>
                    <button type="button" onclick="document.execCommand('justifyRight', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded text-black border border-transparent hover:border-slate-300" title="Align Right">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M10 12h10M4 18h16" /></svg>
                    </button>
                    <div class="w-px h-6 bg-slate-300 mx-1"></div>
                    <button type="button" onclick="document.execCommand('insertUnorderedList', false, null)" class="w-8 h-8 flex items-center justify-center hover:bg-slate-100 rounded text-black border border-transparent hover:border-slate-300" title="Bullet List">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <div class="ml-auto text-xs text-slate-500 italic px-2">Blok teks di bawah lalu klik tombol untuk mengedit</div>
                </div>

                {{-- KERTAS BPN WYSIWYG --}}
"""
content = content.replace("                {{-- KERTAS BPN WYSIWYG --}}\n", toolbar_html)

# 2. Replace $el.innerText with $el.innerHTML
content = content.replace("$el.innerText", "$el.innerHTML")

# 3. Replace {{ $simponi_data[...] ?? '' }} with {!! $simponi_data[...] ?? '' !!}
content = re.sub(r"\{\{\s*(\$simponi_data\[.*?\]\s*\?\?\s*'.*?')\s*\}\}", r"{!! \1 !!}", content)

with open(dashboard_path, "w", encoding="utf-8") as f:
    f.write(content)


pdf_path = r"d:\laragon\www\SI-PNBP\resources\views\pdf\simponi-bpn.blade.php"
with open(pdf_path, "r", encoding="utf-8") as f:
    pdf_content = f.read()

# 4. Replace {{ $data[...] ?? '' }} with {!! $data[...] ?? '' !!} in the PDF view
# Watch out for strtoupper() which might wrap it, we should remove strtoupper or leave it.
# Wait, strtoupper() on HTML will break tags! <B> -> <B> is fine, but <span style="..."> -> <SPAN STYLE="..."> might break?
# Actually, we should remove strtoupper and let the user type it uppercase if they want.
pdf_content = re.sub(r"\{\{\s*strtoupper\((\$data\[.*?\]\s*\?\?\s*'.*?')\)\s*\}\}", r"{!! \1 !!}", pdf_content)
pdf_content = re.sub(r"\{\{\s*(\$data\[.*?\]\s*\?\?\s*'.*?')\s*\}\}", r"{!! \1 !!}", pdf_content)

with open(pdf_path, "w", encoding="utf-8") as f:
    f.write(pdf_content)

print("Patch applied successfully.")
