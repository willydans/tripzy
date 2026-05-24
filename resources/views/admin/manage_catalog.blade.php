<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Catalog - Admin Tripzy</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #F4F7FC; }
        .sidebar-bg { background: linear-gradient(180deg, #627AA3 0%, #859BBE 100%); }
        .topbar-bg { background: linear-gradient(to right, #B2C5E5 0%, #F4F7FC 50%); }
        .card-shadow { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); }
        .glass-search { background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.4); }
        
        /* Modal Animation */
        .modal { transition: opacity 0.25s ease; }
        body.modal-active { overflow-y: hidden !important; }

        /* Sembunyikan scrollbar untuk elemen tertentu di mobile */
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }

        /* Custom Input Form Sesuai UI */
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 700;
            color: #6B85B5;
            margin-bottom: 0.5rem;
        }
        .form-input {
            width: 100%;
            border: 1px solid #B2C5E5;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #1C2C4A;
            background-color: white;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: #5C72A6;
            box-shadow: 0 0 0 3px rgba(92, 114, 166, 0.1);
        }
        .form-input::placeholder {
            color: #B2C5E5;
        }
        select.form-input {
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%236B85B5%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E');
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 0.65em auto;
            color: #8CA1C4;
        }
        select.form-input option { color: #1C2C4A; }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-[#1C2C4A]">

    @if(session('success'))
    <div id="toastNotification" class="fixed inset-0 flex items-center justify-center z-[100] bg-black/40 backdrop-blur-sm transition-opacity duration-300 px-4">
        <div class="bg-[#5C72A6] rounded-2xl p-6 md:p-8 flex flex-col items-center shadow-2xl relative w-full max-w-sm transform scale-100 transition-transform">
            <button onclick="closeToast()" class="absolute top-4 right-4 text-white hover:text-gray-200 text-xl"><i class="fa-solid fa-xmark"></i></button>
            <div class="w-12 h-12 md:w-16 md:h-16 bg-[#22C55E] rounded-full flex items-center justify-center text-white text-2xl md:text-3xl mb-4 shadow-lg">
                <i class="fa-solid fa-check"></i>
            </div>
            <h3 class="text-white text-lg md:text-xl font-bold text-center leading-snug">{{ session('success') }}</h3>
        </div>
    </div>
    <script>
        setTimeout(() => closeToast(), 3000); 
        function closeToast() {
            const toast = document.getElementById('toastNotification');
            if(toast) { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }
        }
    </script>
    @endif

    @if($errors->any())
    <div id="errorNotification" class="fixed inset-0 flex items-center justify-center z-[100] bg-black/40 backdrop-blur-sm transition-opacity duration-300 px-4">
        <div class="bg-white rounded-2xl p-6 md:p-8 flex flex-col items-center shadow-2xl relative w-full max-w-sm transform scale-100 transition-transform border border-red-100">
            <button onclick="closeErrorToast()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-xl transition"><i class="fa-solid fa-xmark"></i></button>
            <div class="w-12 h-12 md:w-16 md:h-16 bg-red-500 rounded-full flex items-center justify-center text-white text-2xl md:text-3xl mb-4 shadow-lg">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="text-[#1C2C4A] text-lg md:text-xl font-bold text-center leading-snug mb-2">Gagal Menyimpan!</h3>
            <ul class="text-xs md:text-sm text-red-500 font-medium text-center list-none space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button onclick="closeErrorToast()" class="mt-6 w-full bg-[#F4F7FC] text-[#6B85B5] font-bold py-2 rounded-full hover:bg-gray-200 transition">Tutup</button>
        </div>
    </div>
    <script>
        function closeErrorToast() {
            const errorToast = document.getElementById('errorNotification');
            if(errorToast) { 
                errorToast.style.opacity = '0'; 
                setTimeout(() => errorToast.remove(), 300); 
            }
        }
    </script>
    @endif

    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden transition-opacity opacity-0" onclick="toggleSidebar()"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 sidebar-bg text-white flex flex-col h-full shadow-2xl md:shadow-lg z-40 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 shrink-0">
        <div class="p-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold italic drop-shadow-md mb-1">Tripzy</h1>
                <p class="text-sm font-medium opacity-90">Admin Panel</p>
            </div>
            <button onclick="toggleSidebar()" class="md:hidden text-white text-2xl hover:text-gray-200"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <nav class="mt-2 md:mt-6 flex-grow flex flex-col gap-2 px-4 overflow-y-auto hide-scroll pb-4">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 hover:opacity-100 transition">Dashboard</a>
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 hover:opacity-100 transition">Booking Verification</a>
            <a href="{{ route('admin.catalog.index') }}" class="flex items-center gap-3 px-4 py-3 bg-white/20 rounded-lg font-semibold border border-white/30 backdrop-blur-sm shadow-sm transition">Manage Catalog</a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 hover:opacity-100 transition">User Management</a>
            <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg font-medium opacity-80 transition">Transactions</a>
        </nav>
    </aside>

    <div class="flex-grow flex flex-col h-full overflow-y-auto relative bg-[#F4F7FC]">
        
        <header class="topbar-bg px-4 md:px-8 py-4 md:py-5 flex justify-between items-center sticky top-0 z-20 shadow-sm md:shadow-none">
            <div class="flex items-center gap-3 md:gap-0">
                <button onclick="toggleSidebar()" class="md:hidden text-[#4A6EB0] text-2xl focus:outline-none">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="relative w-full max-w-[200px] md:max-w-xs lg:w-96 hidden sm:block">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-[#4A6EB0] md:text-white"></i>
                    <input type="text" placeholder="Search...." class="w-full glass-search text-[#4A6EB0] md:text-white placeholder-[#8CA1C4] md:placeholder-white rounded-full py-2 md:py-2.5 pl-10 md:pl-12 pr-4 focus:outline-none focus:bg-white/30 transition shadow-inner text-sm md:text-base pointer-events-none opacity-50" readonly>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <form action="{{ route('logout') }}" method="POST" class="m-0 hidden sm:block">
                    @csrf
                    <button type="submit" class="text-xs text-red-500 font-semibold hover:underline">Logout</button>
                </form>
                <div class="flex items-center gap-2 md:gap-3 bg-white/60 md:bg-white/40 px-3 md:px-4 py-1.5 md:py-2 rounded-full border border-white/50 backdrop-blur-sm shadow-sm">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-[#1C2C4A] rounded-full flex items-center justify-center text-white text-sm md:text-base"><i class="fa-solid fa-user"></i></div>
                    <div class="leading-tight hidden sm:block">
                        <h4 class="text-xs md:text-sm font-bold">{{ Auth::user()->name }}</h4>
                        <p class="text-[9px] md:text-[10px] font-medium text-[#4A6EB0]">Super Admin</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="m-0 sm:hidden ml-2 border-l border-gray-300 pl-2">
                        @csrf
                        <button type="submit" class="text-[#1C2C4A]"><i class="fa-solid fa-right-from-bracket"></i></button>
                    </form>
                </div>
            </div>
        </header>

        <main class="p-4 md:p-8 pb-24">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 md:mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-[#4A6EB0]">Manage Catalog</h2>
                    <p class="text-xs md:text-sm text-gray-500">Kelola katalog mobil sewa dan ketersediaan stok</p>
                </div>
                <button onclick="toggleModal('addCarModal')" class="w-full sm:w-auto justify-center bg-[#5C72A6] hover:bg-[#4A5D8A] text-white font-semibold py-2.5 px-6 rounded-full shadow-md transition flex items-center gap-2 text-sm md:text-base">
                    <i class="fa-solid fa-plus"></i> Add Car
                </button>
            </div>

            <div class="flex flex-col lg:flex-row gap-3 md:gap-4 mb-6">
                <div class="relative flex-grow w-full lg:max-w-md">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-[#8CA1C4]"></i>
                    <input type="text" id="searchInput" placeholder="Search car name or plate...." class="w-full bg-[#DCE4F2] text-[#4A6EB0] placeholder-[#8CA1C4] rounded-full py-2.5 pl-12 pr-4 focus:outline-none focus:ring-2 focus:ring-[#8CA1C4] transition text-sm">
                </div>
                <div class="flex gap-2 md:gap-4 overflow-x-auto hide-scroll pb-1">
                    <select id="statusFilter" class="cursor-pointer bg-[#DCE4F2] text-[#4A6EB0] text-sm md:text-base font-medium rounded-full py-2.5 px-5 md:px-6 appearance-none pr-8 md:pr-10 relative bg-no-repeat bg-[right_1rem_center] bg-[length:1em_1em] whitespace-nowrap outline-none">
                        <option value="All">All Statuses</option>
                        <option value="Tersedia">Tersedia</option>
                        <option value="Disewa">Disewa</option>
                        <option value="Maintenance">Maintenance / Diservis</option>
                    </select>
                    <select id="categoryFilter" class="cursor-pointer bg-[#DCE4F2] text-[#4A6EB0] text-sm md:text-base font-medium rounded-full py-2.5 px-5 md:px-6 appearance-none pr-8 md:pr-10 relative bg-no-repeat bg-[right_1rem_center] bg-[length:1em_1em] whitespace-nowrap outline-none">
                        <option value="All">All Categories</option>
                        @php
                            $categories = $cars->pluck('category')->unique();
                        @endphp
                        @foreach($categories as $category)
                            @php
                                $filterSlug = \Illuminate\Support\Str::slug($category);
                            @endphp
                            <option value="{{ $filterSlug }}">{{ trim($category) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="bg-white rounded-2xl card-shadow overflow-hidden border border-gray-100 w-full overflow-x-auto">
                <table class="w-full min-w-[800px] text-left border-collapse" id="catalogTable">
                    <thead>
                        <tr class="bg-white text-[10px] uppercase tracking-widest text-black font-bold border-b border-gray-100">
                            <th class="py-4 px-6">CAR</th>
                            <th class="py-4 px-6">PLATE</th>
                            <th class="py-4 px-6">CATEGORY</th>
                            <th class="py-4 px-6 text-center">QTY</th>
                            <th class="py-4 px-6">STATUS</th>
                            <th class="py-4 px-6">HARGA/HARI</th>
                            <th class="py-4 px-6 text-center">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($cars as $car)
                        @php
                            // Data string untuk Javascript
                            $searchString = strtolower($car->name . ' ' . $car->license_plate);
                            $carCategorySlug = \Illuminate\Support\Str::slug($car->category);
                            
                            $uiStatus = $car->status;
                            if($car->status == 'Maintenance' || $car->status == 'Diservis') $uiStatus = 'Maintenance';
                        @endphp
                        <tr class="car-row border-b border-gray-50 hover:bg-gray-50 transition"
                            data-search="{{ $searchString }}"
                            data-status="{{ $uiStatus }}"
                            data-category="{{ $carCategorySlug }}">
                            
                            <td class="py-3 px-6 flex items-center gap-4">
                                <img src="{{ asset($car->image_path) }}" alt="{{ $car->name }}" class="w-16 h-10 object-contain shrink-0">
                                <div>
                                    <p class="font-bold text-[#1C2C4A] whitespace-nowrap">{{ $car->name }}</p>
                                    <p class="text-[10px] text-[#8CA1C4] whitespace-nowrap">{{ $car->year }} . {{ $car->color }}</p>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-[#4A6EB0] font-medium whitespace-nowrap">{{ $car->license_plate }}</td>
                            <td class="py-3 px-6 text-[#4A6EB0] whitespace-nowrap">{{ $car->category }}</td>
                            
                            <td class="py-3 px-6 text-center">
                                <span class="font-bold text-[#1C2C4A] bg-[#EBF1FA] px-3 py-1 rounded-full text-xs border border-[#B2C5E5]">
                                    {{ $car->stock ?? 1 }} Unit
                                </span>
                            </td>

                            <td class="py-3 px-6">
                                @if($car->status == 'Tersedia')
                                    <span class="bg-green-100 text-green-600 px-3 py-1 text-[10px] rounded-full font-bold whitespace-nowrap">Tersedia</span>
                                @elseif($car->status == 'Disewa')
                                    <span class="bg-orange-100 text-orange-600 px-3 py-1 text-[10px] rounded-full font-bold whitespace-nowrap">Disewa</span>
                                @else
                                    <span class="bg-red-100 text-red-600 px-3 py-1 text-[10px] rounded-full font-bold whitespace-nowrap">Diservis</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-[#4A6EB0] font-medium whitespace-nowrap">Rp {{ number_format($car->price_per_day, 0, ',', '.') }}</td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <button onclick="openEditModal({{ json_encode($car) }})" class="text-[#4A6EB0] bg-[#DCE4F2] hover:bg-[#4A6EB0] hover:text-white w-8 h-8 rounded-full transition flex items-center justify-center">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>
                                    <button onclick="openDeleteModal({{ $car->id }}, '{{ addslashes($car->name) }}')" class="text-red-500 bg-red-100 hover:bg-red-500 hover:text-white w-8 h-8 rounded-full transition flex items-center justify-center">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="noResultMsg" class="hidden text-center py-8 text-gray-400 font-medium w-full">
                    <i class="fa-solid fa-car-side text-3xl mb-2 opacity-50"></i><br>
                    Data mobil tidak ditemukan
                </div>
            </div>
        </main>
    </div>

    <div id="addCarModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal px-4 py-6">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('addCarModal')"></div>
        <div class="bg-white rounded-3xl w-full max-w-3xl max-h-[95vh] overflow-y-auto relative shadow-2xl p-6 md:p-10 z-10 hide-scroll border border-gray-100">
            
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#5C72A6] rounded-full flex items-center justify-center text-white shrink-0 shadow-md">
                        <i class="fa-solid fa-plus text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-[#5C72A6]">Add Car</h2>
                        <p class="text-xs md:text-sm text-[#8CA1C4]">Add new cars to the catalog</p>
                    </div>
                </div>
                <button onclick="toggleModal('addCarModal')" class="text-[#8CA1C4] hover:text-red-500 text-2xl p-2 transition"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form action="{{ route('admin.catalog.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mb-8">
                    
                    <div>
                        <label class="form-label">Car Models <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Example: Toyota Rush" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Number plate <span class="text-red-500">*</span></label>
                        <input type="text" name="license_plate" placeholder="Example: BE 4829 ZI" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Category</label>
                        <select name="category" class="form-input" onchange="this.style.color='#1C2C4A'">
                            <option value="SUV">SUV</option>
                            <option value="MPV">MPV</option>
                            <option value="Prem SUV">Prem SUV</option>
                            <option value="Prem MPV">Prem MPV</option>
                            <option value="Luxury">Luxury</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input" onchange="this.style.color='#1C2C4A'">
                            <option value="Tersedia">Tersedia</option>
                            <option value="Disewa">Disewa</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Price per Day (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price_per_day" placeholder="Example: 800000" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Year <span class="text-red-500">*</span></label>
                        <input type="number" name="year" placeholder="Example: 2026" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Color <span class="text-red-500">*</span></label>
                        <input type="text" name="color" placeholder="Example: Red" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Fuel</label>
                        <select name="fuel_type" class="form-input" onchange="this.style.color='#1C2C4A'">
                            <option value="Bensin">Bensin</option>
                            <option value="Pertamax">Pertamax</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Listrik">Listrik</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Transmission</label>
                        <select name="transmission" class="form-input" onchange="this.style.color='#1C2C4A'">
                            <option value="Automatik">Automatik</option>
                            <option value="Manual">Manual</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Seat Count <span class="text-red-500">*</span></label>
                        <input type="number" name="seats" placeholder="Example: 6" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Stock / Qty <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" placeholder="Example: 5" value="1" min="1" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Last Servis Date</label>
                        <input type="date" name="service_date" class="form-input" style="color: #8CA1C4;" onchange="this.style.color='#1C2C4A'">
                    </div>

                    <div class="col-span-1 sm:col-span-2">
                        <label class="form-label">Description / Features & Facilities</label>
                        <textarea name="description" rows="4" placeholder="Example: AC Dingin, Audio Premium, Kamera Parkir 360..." class="form-input resize-y"></textarea>
                    </div>

                </div>
                
                <div class="mb-8">
                    <label class="form-label">Car Pictures <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-[#B2C5E5] rounded-2xl p-6 md:p-10 flex flex-col items-center justify-center cursor-pointer hover:bg-[#F4F7FC] transition relative h-40 md:h-48 overflow-hidden group">
                        <div id="upload-text" class="flex flex-col items-center pointer-events-none text-center">
                            <i class="fa-solid fa-cloud-arrow-up text-4xl md:text-5xl text-[#6B85B5] mb-3 group-hover:scale-110 transition"></i>
                            <p class="text-sm md:text-base text-[#6B85B5] font-medium">Klik to upload or drag and drop</p>
                            <p class="text-xs text-[#8CA1C4] mt-1">JPG, JPEG, PNG (max size 200Mb)</p>
                        </div>
                        <img id="image-preview" src="" class="hidden max-h-full max-w-full object-contain z-10 pointer-events-none drop-shadow-md">
                        <input type="file" name="image" id="image-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept="image/*" required onchange="previewImage(event, 'image-preview', 'upload-text')">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                    <button type="button" onclick="toggleModal('addCarModal')" class="w-full sm:w-auto px-10 py-3 rounded-full border-2 border-[#6B85B5] text-[#6B85B5] font-bold hover:bg-[#F4F7FC] transition text-sm md:text-base">Cancel</button>
                    <button type="submit" class="w-full sm:w-auto px-12 py-3 rounded-full bg-[#5C72A6] text-white font-bold hover:bg-[#4A5D8A] transition shadow-lg text-sm md:text-base">Add Car</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editCarModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal px-4 py-6">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('editCarModal')"></div>
        <div class="bg-white rounded-3xl w-full max-w-3xl max-h-[95vh] overflow-y-auto relative shadow-2xl p-6 md:p-10 z-10 hide-scroll border border-gray-100">
            
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#5C72A6] rounded-full flex items-center justify-center text-white shrink-0 shadow-md">
                        <i class="fa-solid fa-plus text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-[#5C72A6]">Edit Car</h2>
                        <p class="text-xs md:text-sm text-[#8CA1C4]">Update car data in the catalog</p>
                    </div>
                </div>
                <button onclick="toggleModal('editCarModal')" class="text-[#8CA1C4] hover:text-red-500 text-2xl p-2 transition"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form id="editCarForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="flex flex-col justify-center items-center mb-10 relative">
                    <div class="border border-[#B2C5E5] rounded-2xl p-4 w-full sm:w-[400px] h-48 flex items-center justify-center bg-white mb-5 shadow-sm relative overflow-hidden">
                        <img id="edit_image_preview" src="" class="max-w-full max-h-full object-contain drop-shadow-md z-10">
                    </div>
                    <label class="bg-[#5C72A6] text-white px-8 py-2.5 rounded-full font-bold cursor-pointer hover:bg-[#4A5D8A] transition text-sm md:text-base shadow-md">
                        Edit Image
                        <input type="file" name="image" id="edit-image-input" class="hidden" accept="image/*" onchange="previewEditImage(event)">
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mb-8">
                    <div>
                        <label class="form-label">Car Models <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Number plate <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_plate" name="license_plate" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Category</label>
                        <select id="edit_category" name="category" class="form-input" style="color:#1C2C4A;">
                            <option value="SUV">SUV</option>
                            <option value="MPV">MPV</option>
                            <option value="Prem SUV">Prem SUV</option>
                            <option value="Prem MPV">Prem MPV</option>
                            <option value="Luxury">Luxury</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select id="edit_status" name="status" class="form-input" style="color:#1C2C4A;">
                            <option value="Tersedia">Tersedia</option>
                            <option value="Disewa">Disewa</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Price per Day (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_price" name="price_per_day" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Year <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_year" name="year" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Color <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_color" name="color" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Fuel</label>
                        <select id="edit_fuel" name="fuel_type" class="form-input" style="color:#1C2C4A;">
                            <option value="Bensin">Bensin</option>
                            <option value="Pertamax">Pertamax</option>
                            <option value="Diesel">Diesel</option>
                            <option value="Listrik">Listrik</option>
                            <option value="Hybrid">Hybrid</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Transmission</label>
                        <select id="edit_transmission" name="transmission" class="form-input" style="color:#1C2C4A;">
                            <option value="Automatik">Automatik</option>
                            <option value="Manual">Manual</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Seat Count <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_seats" name="seats" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Stock / Qty <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_stock" name="stock" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Last Servis Date</label>
                        <input type="date" id="edit_service_date" name="service_date" class="form-input" style="color:#1C2C4A;">
                    </div>

                    <div class="col-span-1 sm:col-span-2">
                        <label class="form-label">Description / Features & Facilities</label>
                        <textarea id="edit_description" name="description" rows="4" placeholder="Example: AC Dingin, Audio Premium, Kamera Parkir 360..." class="form-input resize-y"></textarea>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                    <button type="button" onclick="toggleModal('editCarModal')" class="w-full sm:w-auto px-10 py-3 rounded-full border-2 border-[#6B85B5] text-[#6B85B5] font-bold hover:bg-[#F4F7FC] transition text-sm md:text-base">Cancel</button>
                    <button type="submit" class="w-full sm:w-auto px-12 py-3 rounded-full bg-[#5C72A6] text-white font-bold hover:bg-[#4A5D8A] transition shadow-lg text-sm md:text-base">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteCarModal" class="fixed inset-0 z-[60] flex items-center justify-center opacity-0 pointer-events-none modal px-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('deleteCarModal')"></div>
        <div class="bg-white rounded-2xl w-full max-w-sm md:max-w-md relative shadow-2xl p-6 md:p-8 z-10 text-center">
            <div class="w-14 h-14 md:w-16 md:h-16 bg-red-100 rounded-full flex items-center justify-center text-red-500 text-xl md:text-2xl mx-auto mb-4">
                <i class="fa-regular fa-trash-can"></i>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-[#4A6EB0] mb-2">Delete Car?</h2>
            <p class="text-[#8CA1C4] mb-8 text-sm md:text-base">Are you sure you want to delete <strong id="delete_car_name" class="text-[#4A6EB0]"></strong>?<br>This action cannot be undone.</p>
            
            <form id="deleteCarForm" method="POST" class="flex justify-center gap-3 md:gap-4 w-full">
                @csrf
                @method('DELETE')
                <button type="button" onclick="toggleModal('deleteCarModal')" class="flex-1 py-2.5 md:py-3 rounded-full border border-[#4A6EB0] text-[#4A6EB0] font-bold hover:bg-gray-50 transition text-sm">Cancel</button>
                <button type="submit" class="flex-1 py-2.5 md:py-3 rounded-full bg-red-500 text-white font-bold hover:bg-red-600 transition shadow-md text-sm">Delete</button>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            
            if(modalID === 'addCarModal' && !modal.classList.contains('opacity-0')) {
                document.getElementById('image-preview').classList.add('hidden');
                document.getElementById('upload-text').classList.remove('hidden');
                document.getElementById('image-preview').src = '';
                document.getElementById('image-input').value = '';
            }

            if (modal.classList.contains('opacity-0')) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                document.body.classList.add('modal-active');
            } else {
                modal.classList.add('opacity-0', 'pointer-events-none');
                document.body.classList.remove('modal-active');
            }
        }

        function previewImage(event, previewId, textId) {
            const input = event.target;
            const preview = document.getElementById(previewId);
            const text = document.getElementById(textId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    text.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = "";
                preview.classList.add('hidden');
                text.classList.remove('hidden');
            }
        }

        function previewEditImage(event) {
            const input = event.target;
            const preview = document.getElementById('edit_image_preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function openEditModal(car) {
            document.getElementById('editCarForm').action = `/admin/catalog/${car.id}`;
            document.getElementById('edit_name').value = car.name;
            document.getElementById('edit_plate').value = car.license_plate;
            document.getElementById('edit_category').value = car.category;
            document.getElementById('edit_status').value = car.status;
            document.getElementById('edit_price').value = car.price_per_day;
            document.getElementById('edit_stock').value = car.stock !== null ? car.stock : 1;
            document.getElementById('edit_year').value = car.year;
            document.getElementById('edit_color').value = car.color;
            document.getElementById('edit_fuel').value = car.fuel_type;
            document.getElementById('edit_transmission').value = car.transmission;
            document.getElementById('edit_seats').value = car.seats;
            
            // 🟢 TAMPILIN DATA DESKRIPSI KE TEXTAREA 🟢
            document.getElementById('edit_description').value = car.description || '';
            
            if(car.service_date) {
                document.getElementById('edit_service_date').value = car.service_date.split('T')[0];
            }
            
            document.getElementById('edit_image_preview').src = `/${car.image_path}`;
            document.getElementById('edit-image-input').value = ""; 
            
            toggleModal('editCarModal');
        }

        function openDeleteModal(carId, carName) {
            document.getElementById('deleteCarForm').action = `/admin/catalog/${carId}`;
            document.getElementById('delete_car_name').innerText = carName;
            toggleModal('deleteCarModal');
        }

        // LOGIKA FILTER DAN SEARCH REAL-TIME
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const categoryFilter = document.getElementById('categoryFilter');
            const rows = document.querySelectorAll('.car-row');
            const noResultMsg = document.getElementById('noResultMsg');

            function filterTable() {
                const query = searchInput.value.toLowerCase().trim();
                const status = statusFilter.value;
                const category = categoryFilter.value;
                let visibleCount = 0;

                rows.forEach(row => {
                    const rowSearch = row.getAttribute('data-search');
                    const rowStatus = row.getAttribute('data-status');
                    const rowCategory = row.getAttribute('data-category');

                    const matchSearch = rowSearch.includes(query);
                    const matchStatus = (status === 'All') || (rowStatus === status);
                    const matchCategory = (category === 'All') || (rowCategory === category);

                    if (matchSearch && matchStatus && matchCategory) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (visibleCount === 0) {
                    noResultMsg.classList.remove('hidden');
                } else {
                    noResultMsg.classList.add('hidden');
                }
            }

            searchInput.addEventListener('input', filterTable);
            statusFilter.addEventListener('change', filterTable);
            categoryFilter.addEventListener('change', filterTable);
        });
    </script>
    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (typeof window.performance != "undefined" && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>