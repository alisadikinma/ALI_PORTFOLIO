<!-- Awards & Recognition Section - REDESIGNED -->
@if($konf->awards_section_active ?? true)
<section id="awards" class="w-full py-16 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-yellow-400 text-5xl sm:text-6xl font-bold mb-4">
                Awards & Recognition
            </h2>
            <p class="text-gray-400 text-lg sm:text-xl max-w-3xl mx-auto">
                Innovative solutions that drive real business impact and transformation
            </p>
        </div>

        @if(isset($award) && $award->count() > 0)
        <!-- Awards Grid - NEW DESIGN -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($award as $index => $row)
            @php
            // Logo colors based on company
            $logoConfigs = [
                'nextdev' => ['bg' => '#4A90E2', 'text' => 'NextDev', 'subtitle' => 'Telkomsel • 2018', 'color' => 'blue'],
                'alibaba' => ['bg' => '#FF6A00', 'text' => 'Alibaba', 'subtitle' => 'ALIBABA UNCTAD • 2019', 'color' => 'orange'],
                'google' => ['bg' => '#4285F4', 'text' => 'Google', 'subtitle' => 'GOOGLE • 2018', 'color' => 'blue'],
                'wild' => ['bg' => '#00C853', 'text' => 'Wild Card', 'subtitle' => 'FENOX • 2017', 'color' => 'green'],
                'fenox' => ['bg' => '#FF4444', 'text' => 'Fenox', 'subtitle' => 'FENOX • 2017', 'color' => 'red'],
                'bubu' => ['bg' => '#00D25B', 'text' => 'BUBU', 'subtitle' => 'BUBU.com • 2017', 'color' => 'green'],
                'grind' => ['bg' => '#4285F4', 'text' => 'Startup Grind', 'subtitle' => 'GOOGLE • 2024', 'color' => 'blue'],
                'default' => ['bg' => '#FFC107', 'text' => 'Award', 'subtitle' => date('Y'), 'color' => 'yellow']
            ];
            
            $logoKey = 'default';
            foreach(array_keys($logoConfigs) as $key) {
                if(stripos($row->nama_award, $key) !== false) {
                    $logoKey = $key;
                    break;
                }
            }
            
            $logoConfig = $logoConfigs[$logoKey];
            @endphp
            
            <div class="award-card-modern group relative bg-slate-800/50 backdrop-blur-sm rounded-2xl border border-slate-700/50 hover:border-slate-600 transition-all duration-300 p-8 cursor-pointer hover:transform hover:-translate-y-1" 
                 onclick="openAwardGallery({{ $row->id_award }}, '{{ addslashes($row->nama_award) }}')">
                
                <!-- Logo Icon -->
                <div class="mb-6">
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center shadow-xl" 
                         style="background: {{ $logoConfig['bg'] }};">
                        @if($row->gambar_award && file_exists(public_path('file/award/' . $row->gambar_award)))
                            <img src="{{ asset('file/award/' . $row->gambar_award) }}" 
                                 alt="{{ $row->nama_award }}" 
                                 class="w-12 h-12 object-contain filter brightness-0 invert" />
                        @else
                            <!-- Default icons based on company -->
                            @if(stripos($row->nama_award, 'nextdev') !== false)
                                <span class="text-white text-2xl font-bold">N</span>
                            @elseif(stripos($row->nama_award, 'google') !== false || stripos($row->nama_award, 'grind') !== false)
                                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                            @elseif(stripos($row->nama_award, 'alibaba') !== false)
                                <span class="text-white text-3xl font-bold">Ali</span>
                            @elseif(stripos($row->nama_award, 'wild') !== false || stripos($row->nama_award, 'fenox') !== false)
                                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21 6h-7.59l1.29-1.29a1 1 0 0 0-1.42-1.42l-3 3a1 1 0 0 0 0 1.42l3 3a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42L13.41 8H21a1 1 0 0 0 0-2zM3 12a1 1 0 0 0 0 2h7.59l-1.29 1.29a1 1 0 0 0 0 1.42 1 1 0 0 0 1.42 0l3-3a1 1 0 0 0 0-1.42l-3-3a1 1 0 0 0-1.42 1.42L10.59 12z"/>
                                </svg>
                            @elseif(stripos($row->nama_award, 'bubu') !== false)
                                <span class="text-white text-2xl font-bold">BUBU</span>
                            @else
                                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                                </svg>
                            @endif
                        @endif
                    </div>
                </div>
                
                <!-- Award Title -->
                <h3 class="text-white text-xl font-bold mb-2 leading-tight">
                    {{ $row->nama_award }}
                </h3>
                
                <!-- Company & Year -->
                <p class="text-{{ $logoConfig['color'] }}-400 text-sm font-semibold mb-4 uppercase tracking-wide">
                    {{ $logoConfig['subtitle'] }}
                </p>
                
                <!-- Description -->
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    {!! Str::limit(strip_tags($row->keterangan_award), 150, '...') !!}
                </p>
                
                <!-- View Gallery Button -->
                <button class="flex items-center gap-2 text-gray-400 text-sm font-medium group-hover:text-white transition-colors">
                    <span>VIEW GALLERY</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            @endforeach
        </div>
        
        @else
        <!-- No Data State -->
        <div class="flex flex-col items-center justify-center py-16">
            <div class="text-yellow-400 text-6xl mb-4">🏆</div>
            <h3 class="text-white text-xl font-semibold mb-2">No Awards Yet</h3>
            <p class="text-gray-400 text-center max-w-md">
                We're building our track record of achievements and recognition. Stay tuned to see our upcoming awards and accomplishments!
            </p>
        </div>
        @endif
    </div>
</section>

<style>
/* Awards Section Custom Styles */
.award-card-modern {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.6) 0%, rgba(15, 23, 42, 0.8) 100%);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.award-card-modern:hover {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(15, 23, 42, 0.9) 100%);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
}

/* Custom color classes for companies */
.text-orange-400 { color: #fb923c; }
.text-blue-400 { color: #60a5fa; }
.text-green-400 { color: #4ade80; }
.text-red-400 { color: #f87171; }
.text-yellow-400 { color: #fbbf24; }
</style>
@endif