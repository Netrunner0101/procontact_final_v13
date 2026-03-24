@extends('layouts.sidebar')

@section('title', 'ProContact CRM | Contact Manager')

@section('top-nav-links')
<div class="h-4 w-px bg-stone-200/50"></div>
<nav class="flex gap-6">
<a class="text-sm font-medium text-stone-600 hover:text-primary transition-all" href="{{ route('executive.dashboard') }}">Dashboard</a>
<a class="text-sm font-medium text-primary font-semibold" href="{{ route('executive.contacts') }}">Contact Manager</a>
</nav>
@endsection

@section('content')
<div class="pt-24 px-12 pb-12">
<!-- Header Section -->
<div class="flex justify-between items-end mb-12">
<div class="max-w-xl">
<h2 class="text-4xl font-extrabold tracking-tight text-on-surface mb-2">Directory</h2>
<p class="text-secondary font-medium leading-relaxed">Oversee your high-performance network. Manage relationships through structural status tracking and activity-based segmentation.</p>
</div>
<div class="flex gap-4">
<button class="px-6 py-2.5 bg-surface-container-lowest border border-stone-200 text-stone-600 rounded-lg text-sm font-semibold flex items-center gap-2 hover:bg-stone-50 transition-all">
<span class="material-symbols-outlined text-lg">file_download</span> Export
</button>
<button class="px-6 py-2.5 bg-[#843728] text-white rounded-lg text-sm font-semibold flex items-center gap-2 hover:opacity-90 shadow-lg shadow-primary/10 transition-all">
<span class="material-symbols-outlined text-lg">add</span> New Record
</button>
</div>
</div>
<!-- Dashboard Bento Style Filters -->
<div class="grid grid-cols-12 gap-6 mb-8">
<div class="col-span-12 lg:col-span-8 bg-surface-container-low rounded-xl p-6 flex items-center gap-8 overflow-x-auto">
<div class="shrink-0">
<span class="text-[10px] uppercase tracking-widest font-bold text-stone-400 block mb-2">Pipeline Filter</span>
<div class="flex gap-2">
<button class="px-4 py-1.5 bg-white text-primary text-xs font-bold rounded-full shadow-sm">All Contacts</button>
<button class="px-4 py-1.5 hover:bg-white/50 text-stone-500 text-xs font-medium rounded-full transition-all">Prospect</button>
<button class="px-4 py-1.5 hover:bg-white/50 text-stone-500 text-xs font-medium rounded-full transition-all">Lead qualifi&eacute;</button>
<button class="px-4 py-1.5 hover:bg-white/50 text-stone-500 text-xs font-medium rounded-full transition-all">Proposition</button>
<button class="px-4 py-1.5 hover:bg-white/50 text-stone-500 text-xs font-medium rounded-full transition-all">N&eacute;gociation</button>
</div>
</div>
</div>
<div class="col-span-12 lg:col-span-4 bg-surface-container-low rounded-xl p-6 flex items-center gap-4">
<div class="w-full">
<span class="text-[10px] uppercase tracking-widest font-bold text-stone-400 block mb-2">Quick Search</span>
<div class="relative">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">filter_list</span>
<input class="w-full bg-white border-none rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-1 focus:ring-primary/20" placeholder="Filter by activity tag..." type="text"/>
</div>
</div>
</div>
</div>
<!-- Data Table Section -->
<div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm shadow-stone-900/5">
<div class="overflow-x-auto">
<table class="w-full border-collapse text-left">
<thead>
<tr class="bg-surface-container-high">
<th class="px-6 py-4 text-[10px] uppercase tracking-wider font-bold text-stone-500">Name &amp; Title</th>
<th class="px-6 py-4 text-[10px] uppercase tracking-wider font-bold text-stone-500">Pipeline Status</th>
<th class="px-6 py-4 text-[10px] uppercase tracking-wider font-bold text-stone-500">Contact Details</th>
<th class="px-6 py-4 text-[10px] uppercase tracking-wider font-bold text-stone-500">Address</th>
<th class="px-6 py-4 text-[10px] uppercase tracking-wider font-bold text-stone-500">Activities</th>
<th class="px-6 py-4 text-[10px] uppercase tracking-wider font-bold text-stone-500 text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-stone-100">
<!-- Row 1 -->
<tr class="hover:bg-stone-50 transition-colors group">
<td class="px-6 py-5">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-lg bg-primary/5 flex items-center justify-center text-primary font-bold">EM</div>
<div>
<p class="text-sm font-bold text-on-surface">Elena Moretti</p>
<p class="text-xs text-stone-500">Design Director at Studio A1</p>
</div>
</div>
</td>
<td class="px-6 py-5">
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-tight bg-tertiary-fixed text-on-tertiary-fixed">Lead qualifi&eacute;</span>
</td>
<td class="px-6 py-5">
<p class="text-sm text-stone-600">elena.m@studio-a1.com</p>
<p class="text-xs text-stone-400">+39 342 9882 110</p>
</td>
<td class="px-6 py-5">
<p class="text-sm text-stone-600 truncate max-w-[180px]">Via Brera, 28, Milan, IT</p>
</td>
<td class="px-6 py-5">
<div class="flex flex-wrap gap-1.5">
<span class="px-2 py-0.5 rounded bg-secondary-container text-on-secondary-container text-[10px] font-medium">Architecture</span>
<span class="px-2 py-0.5 rounded bg-secondary-container text-on-secondary-container text-[10px] font-medium">Follow-up</span>
</div>
</td>
<td class="px-6 py-5 text-right">
<button class="text-stone-400 hover:text-primary transition-colors"><span class="material-symbols-outlined">more_horiz</span></button>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-stone-50 transition-colors group">
<td class="px-6 py-5">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-lg bg-primary/5 flex items-center justify-center text-primary font-bold">JW</div>
<div>
<p class="text-sm font-bold text-on-surface">Jonathan Wright</p>
<p class="text-xs text-stone-500">CEO, Wright &amp; Co.</p>
</div>
</div>
</td>
<td class="px-6 py-5">
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-tight bg-primary-fixed text-on-primary-fixed-variant">N&eacute;gociation</span>
</td>
<td class="px-6 py-5">
<p class="text-sm text-stone-600">j.wright@wright-co.com</p>
<p class="text-xs text-stone-400">+1 (555) 012-9844</p>
</td>
<td class="px-6 py-5">
<p class="text-sm text-stone-600 truncate max-w-[180px]">5th Ave, NY, USA</p>
</td>
<td class="px-6 py-5">
<div class="flex flex-wrap gap-1.5">
<span class="px-2 py-0.5 rounded bg-secondary-container text-on-secondary-container text-[10px] font-medium">VIP</span>
<span class="px-2 py-0.5 rounded bg-secondary-container text-on-secondary-container text-[10px] font-medium">High Margin</span>
<span class="px-2 py-0.5 rounded bg-secondary-container text-on-secondary-container text-[10px] font-medium">+2</span>
</div>
</td>
<td class="px-6 py-5 text-right">
<button class="text-stone-400 hover:text-primary transition-colors"><span class="material-symbols-outlined">more_horiz</span></button>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-stone-50 transition-colors group">
<td class="px-6 py-5">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-lg bg-primary/5 flex items-center justify-center text-primary font-bold">SD</div>
<div>
<p class="text-sm font-bold text-on-surface">Sarah Dupont</p>
<p class="text-xs text-stone-500">Project Manager</p>
</div>
</div>
</td>
<td class="px-6 py-5">
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-tight bg-surface-container-high text-stone-600">Prospect</span>
</td>
<td class="px-6 py-5">
<p class="text-sm text-stone-600">s.dupont@pm.fr</p>
<p class="text-xs text-stone-400">+33 1 45 67 89 00</p>
</td>
<td class="px-6 py-5">
<p class="text-sm text-stone-600 truncate max-w-[180px]">Rue de Rivoli, Paris, FR</p>
</td>
<td class="px-6 py-5">
<div class="flex flex-wrap gap-1.5">
<span class="px-2 py-0.5 rounded bg-secondary-container text-on-secondary-container text-[10px] font-medium">New</span>
</div>
</td>
<td class="px-6 py-5 text-right">
<button class="text-stone-400 hover:text-primary transition-colors"><span class="material-symbols-outlined">more_horiz</span></button>
</td>
</tr>
<!-- Row 4 -->
<tr class="hover:bg-stone-50 transition-colors group">
<td class="px-6 py-5">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-lg bg-primary/5 flex items-center justify-center text-primary font-bold">AK</div>
<div>
<p class="text-sm font-bold text-on-surface">Alex Kim</p>
<p class="text-xs text-stone-500">Investment Partner</p>
</div>
</div>
</td>
<td class="px-6 py-5">
<span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-tight bg-tertiary-fixed text-on-tertiary-fixed">Proposition</span>
</td>
<td class="px-6 py-5">
<p class="text-sm text-stone-600">a.kim@ventured.co</p>
<p class="text-xs text-stone-400">+1 (415) 555-0101</p>
</td>
<td class="px-6 py-5">
<p class="text-sm text-stone-600 truncate max-w-[180px]">Market St, SF, USA</p>
</td>
<td class="px-6 py-5">
<div class="flex flex-wrap gap-1.5">
<span class="px-2 py-0.5 rounded bg-secondary-container text-on-secondary-container text-[10px] font-medium">VC</span>
<span class="px-2 py-0.5 rounded bg-secondary-container text-on-secondary-container text-[10px] font-medium">Tech</span>
</div>
</td>
<td class="px-6 py-5 text-right">
<button class="text-stone-400 hover:text-primary transition-colors"><span class="material-symbols-outlined">more_horiz</span></button>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="px-6 py-4 flex justify-between items-center border-t border-stone-100 bg-surface-container-low/30">
<p class="text-xs text-stone-500">Showing 4 of 1,240 contacts</p>
<div class="flex gap-2">
<button class="px-3 py-1 text-xs font-bold text-stone-400 cursor-not-allowed">Previous</button>
<div class="flex gap-1">
<button class="w-6 h-6 rounded flex items-center justify-center bg-primary text-white text-xs font-bold">1</button>
<button class="w-6 h-6 rounded flex items-center justify-center hover:bg-stone-200 text-stone-600 text-xs font-bold transition-colors">2</button>
<button class="w-6 h-6 rounded flex items-center justify-center hover:bg-stone-200 text-stone-600 text-xs font-bold transition-colors">3</button>
</div>
<button class="px-3 py-1 text-xs font-bold text-primary hover:bg-primary/5 rounded transition-all">Next</button>
</div>
</div>
</div>
<!-- Dashboard Stat Grid (Bento) -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-12">
<div class="bg-surface-container rounded-xl p-6">
<span class="text-[10px] uppercase tracking-widest font-bold text-stone-400 block mb-1">Total Assets</span>
<h3 class="text-2xl font-extrabold text-on-surface">1,240</h3>
<p class="text-[10px] text-primary font-bold mt-2">+12% from last month</p>
</div>
<div class="bg-surface-container rounded-xl p-6">
<span class="text-[10px] uppercase tracking-widest font-bold text-stone-400 block mb-1">Active Deals</span>
<h3 class="text-2xl font-extrabold text-on-surface">84</h3>
<p class="text-[10px] text-stone-500 font-bold mt-2">Valued at $2.4M</p>
</div>
<div class="bg-surface-container rounded-xl p-6">
<span class="text-[10px] uppercase tracking-widest font-bold text-stone-400 block mb-1">Response Rate</span>
<h3 class="text-2xl font-extrabold text-on-surface">92%</h3>
<div class="w-full bg-stone-200 h-1 rounded-full mt-4">
<div class="bg-primary h-1 rounded-full w-[92%]"></div>
</div>
</div>
<div class="bg-surface-container-high rounded-xl p-6 flex items-center justify-between group cursor-pointer hover:bg-stone-200 transition-all">
<div>
<span class="text-[10px] uppercase tracking-widest font-bold text-stone-400 block mb-1">System Health</span>
<h3 class="text-lg font-bold text-on-surface">Architect Tier</h3>
</div>
<span class="material-symbols-outlined text-stone-400 group-hover:text-primary group-hover:translate-x-1 transition-all">arrow_forward</span>
</div>
</div>
</div>

<!-- Modal Backdrop (Hidden) -->
<div class="fixed inset-0 bg-stone-900/40 backdrop-blur-sm z-[60] flex items-center justify-center opacity-0 pointer-events-none transition-opacity">
<div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-8 transform translate-y-4 transition-transform">
<div class="flex justify-between items-start mb-8">
<div>
<h3 class="text-2xl font-extrabold tracking-tight">New Contact Record</h3>
<p class="text-sm text-stone-500">Add a new entry to the CRM architect pool.</p>
</div>
<button class="p-2 hover:bg-stone-100 rounded-full transition-colors">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<div class="space-y-6">
<div class="grid grid-cols-2 gap-4">
<div class="space-y-1.5">
<label class="text-[10px] uppercase tracking-widest font-bold text-stone-400 ml-1">First Name</label>
<input class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-primary/20" type="text"/>
</div>
<div class="space-y-1.5">
<label class="text-[10px] uppercase tracking-widest font-bold text-stone-400 ml-1">Last Name</label>
<input class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-primary/20" type="text"/>
</div>
</div>
<div class="space-y-1.5">
<label class="text-[10px] uppercase tracking-widest font-bold text-stone-400 ml-1">Email Address</label>
<input class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-primary/20" type="email"/>
</div>
<div class="space-y-1.5">
<label class="text-[10px] uppercase tracking-widest font-bold text-stone-400 ml-1">Initial Pipeline Status</label>
<select class="w-full bg-surface-container-low border-none rounded-lg px-4 py-3 text-sm focus:ring-1 focus:ring-primary/20 appearance-none">
<option>Prospect</option>
<option>Lead qualifi&eacute;</option>
<option>Proposition</option>
<option>N&eacute;gociation</option>
</select>
</div>
<div class="pt-6 border-t border-stone-100 flex gap-4">
<button class="flex-1 px-6 py-3 border border-stone-200 text-stone-600 rounded-lg text-sm font-bold hover:bg-stone-50 transition-all">Cancel</button>
<button class="flex-1 px-6 py-3 bg-[#843728] text-white rounded-lg text-sm font-bold hover:opacity-90 transition-all">Create Entry</button>
</div>
</div>
</div>
</div>
@endsection
