import sys

file_path = r'd:\laragon\www\SI-PNBP\resources\views\livewire\admin\audit-dashboard.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

missing_fields_html = """                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Kementerian/Lembaga</label>
                        <textarea wire:model="simponi_data.kementerian_lembaga" rows="1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-y"></textarea>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Unit Eselon I</label>
                        <textarea wire:model="simponi_data.unit_eselon_i" rows="1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-y"></textarea>
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Satuan Kerja</label>
                        <textarea wire:model="simponi_data.satuan_kerja" rows="2" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-y"></textarea>
                    </div>"""

old_wajib_setor = """                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Nama Wajib Setor</label>
                        <input type="text" wire:model="simponi_data.nama_wajib_setor" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>"""

new_wajib_setor = """                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">Nama Wajib Setor / Wajib Bayar</label>
                        <textarea wire:model="simponi_data.nama_wajib_setor" rows="1" class="w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700/50 px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-y"></textarea>
                    </div>\n""" + missing_fields_html

if old_wajib_setor in content:
    content = content.replace(old_wajib_setor, new_wajib_setor)
else:
    print("Could not find Wajib Setor div")

content = content.replace('<input type="text" wire:model="simponi_data.kode_billing"', '<textarea rows="1" wire:model="simponi_data.kode_billing"').replace('transition-all">', 'transition-all resize-y"></textarea>')
content = content.replace('<input type="text" wire:model="simponi_data.tanggal_billing"', '<textarea rows="1" wire:model="simponi_data.tanggal_billing"')
content = content.replace('<input type="text" wire:model="simponi_data.tanggal_kedaluwarsa"', '<textarea rows="1" wire:model="simponi_data.tanggal_kedaluwarsa"')
content = content.replace('<input type="text" wire:model="simponi_data.tanggal_bayar"', '<textarea rows="1" wire:model="simponi_data.tanggal_bayar"')
content = content.replace('<input type="text" wire:model="simponi_data.bank_pos_fintech_bayar"', '<textarea rows="1" wire:model="simponi_data.bank_pos_fintech_bayar"')
content = content.replace('<input type="text" wire:model="simponi_data.channel_bayar"', '<textarea rows="1" wire:model="simponi_data.channel_bayar"')
content = content.replace('<input type="text" wire:model="simponi_data.total_disetor"', '<textarea rows="1" wire:model="simponi_data.total_disetor"')
content = content.replace('<input type="text" wire:model="simponi_data.status"', '<textarea rows="1" wire:model="simponi_data.status"')
content = content.replace('<input type="text" wire:model="simponi_data.terbilang"', '<textarea rows="2" wire:model="simponi_data.terbilang"')
content = content.replace('<input type="text" wire:model="simponi_data.jenis_setoran"', '<textarea rows="2" wire:model="simponi_data.jenis_setoran"')
content = content.replace('<input type="text" wire:model="simponi_data.kode_akun"', '<textarea rows="1" wire:model="simponi_data.kode_akun"')
content = content.replace('<input type="text" wire:model="simponi_data.jumlah_setoran"', '<textarea rows="1" wire:model="simponi_data.jumlah_setoran"')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print('Done')
