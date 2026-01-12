<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SunStore - Products</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 32px;
        }

        .filters {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
        }

        .filter-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-item {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
            color: #555;
            font-size: 14px;
        }

        input, select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .type-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .type-tab {
            padding: 10px 20px;
            border: 2px solid #ddd;
            background-color: white;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: all 0.2s;
        }

        .type-tab:hover {
            border-color: #4CAF50;
            background-color: #f0f0f0;
        }

        .type-tab.active {
            border-color: #4CAF50;
            background-color: #4CAF50;
            color: white;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        button:hover {
            background-color: #45a049;
        }

        .reset-btn {
            background-color: #757575;
            margin-left: 10px;
        }

        .reset-btn:hover {
            background-color: #616161;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f5f5f5;
            font-weight: 600;
            color: #333;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .price {
            font-weight: 600;
            color: #4CAF50;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .pagination nav {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            background-color: white;
            min-width: 40px;
            text-align: center;
            display: inline-block;
        }

        .pagination a:hover {
            background-color: #f0f0f0;
            border-color: #4CAF50;
        }

        .pagination span[aria-current="page"] {
            background-color: #4CAF50;
            color: white;
            border-color: #4CAF50;
            font-weight: 600;
        }

        .pagination .disabled,
        .pagination span[aria-disabled="true"] {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #f5f5f5;
        }

        .pagination svg {
            width: 16px;
            height: 16px;
            display: inline-block;
            vertical-align: middle;
        }

        .pagination .hidden {
            display: none;
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-battery {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .badge-connector {
            background-color: #fff3e0;
            color: #f57c00;
        }

        .badge-solar {
            background-color: #e8f5e9;
            color: #388e3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>SunStore Product Catalog</h1>

        <div class="type-tabs">
            <a href="{{ route('products.index', array_merge(request()->except('type'), ['type' => 'all'])) }}"
               class="type-tab {{ $type === 'all' ? 'active' : '' }}">
                All Products
            </a>
            <a href="{{ route('products.index', array_merge(request()->except('type'), ['type' => 'solar_panel'])) }}"
               class="type-tab {{ $type === 'solar_panel' ? 'active' : '' }}">
                Solar Panels
            </a>
            <a href="{{ route('products.index', array_merge(request()->except('type'), ['type' => 'battery'])) }}"
               class="type-tab {{ $type === 'battery' ? 'active' : '' }}">
                Batteries
            </a>
            <a href="{{ route('products.index', array_merge(request()->except('type'), ['type' => 'connector'])) }}"
               class="type-tab {{ $type === 'connector' ? 'active' : '' }}">
                Connectors
            </a>
        </div>

        <form method="GET" action="{{ route('products.index') }}" class="filters">
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="filter-group">
                <div class="filter-item">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" value="{{ request('name') }}" placeholder="Search by name...">
                </div>

                <div class="filter-item">
                    <label for="manufacturer">Manufacturer</label>
                    <select id="manufacturer" name="manufacturer">
                        <option value="">All Manufacturers</option>
                        @foreach($manufacturers as $mfr)
                            <option value="{{ $mfr }}" {{ request('manufacturer') === $mfr ? 'selected' : '' }}>
                                {{ $mfr }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item">
                    <label for="min_price">Min Price ($)</label>
                    <input type="number" id="min_price" name="min_price" value="{{ request('min_price') }}" step="0.01" placeholder="0.00">
                </div>

                <div class="filter-item">
                    <label for="max_price">Max Price ($)</label>
                    <input type="number" id="max_price" name="max_price" value="{{ request('max_price') }}" step="0.01" placeholder="99999.99">
                </div>
            </div>

            <div class="filter-group">
                <div class="filter-item">
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" value="{{ request('description') }}" placeholder="Search in description...">
                </div>

                @if($type === 'battery')
                    <div class="filter-item">
                        <label for="min_capacity">Min Capacity (kWh)</label>
                        <input type="number" id="min_capacity" name="min_capacity" value="{{ request('min_capacity') }}" step="0.1" placeholder="0">
                    </div>

                    <div class="filter-item">
                        <label for="max_capacity">Max Capacity (kWh)</label>
                        <input type="number" id="max_capacity" name="max_capacity" value="{{ request('max_capacity') }}" step="0.1" placeholder="100">
                    </div>
                @endif

                @if($type === 'connector')
                    <div class="filter-item">
                        <label for="connector_type">Connector Type</label>
                        <select id="connector_type" name="connector_type">
                            <option value="">All Types</option>
                            @foreach($connectorTypes as $ct)
                                <option value="{{ $ct }}" {{ request('connector_type') === $ct ? 'selected' : '' }}>
                                    {{ $ct }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if($type === 'solar_panel')
                    <div class="filter-item">
                        <label for="min_power">Min Power (W)</label>
                        <input type="number" id="min_power" name="min_power" value="{{ request('min_power') }}" placeholder="0">
                    </div>

                    <div class="filter-item">
                        <label for="max_power">Max Power (W)</label>
                        <input type="number" id="max_power" name="max_power" value="{{ request('max_power') }}" placeholder="1000">
                    </div>
                @endif
            </div>

            <div style="margin-top: 15px;">
                <button type="submit">Apply Filters</button>
                <a href="{{ route('products.index', ['type' => $type]) }}" class="reset-btn" style="display: inline-block; padding: 10px 25px; text-decoration: none; border-radius: 4px;">Reset</a>
            </div>
        </form>

        @if($products->count() > 0)
            <div style="margin-bottom: 15px; color: #666;">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Manufacturer</th>
                        <th>Price</th>
                        @if($type === 'all')
                            <th>Specifications</th>
                        @elseif($type === 'battery')
                            <th>Capacity (kWh)</th>
                        @elseif($type === 'connector')
                            <th>Connector Type</th>
                        @elseif($type === 'solar_panel')
                            <th>Power Output (W)</th>
                        @endif
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                @if($product->type === 'battery')
                                    <span class="badge badge-battery">Battery</span>
                                @elseif($product->type === 'connector')
                                    <span class="badge badge-connector">Connector</span>
                                @elseif($product->type === 'solar_panel')
                                    <span class="badge badge-solar">Solar Panel</span>
                                @endif
                            </td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ $product->manufacturer }}</td>
                            <td class="price">${{ number_format($product->price, 2) }}</td>
                            @if($type === 'all')
                                <td>
                                    @php
                                        $attrs = $product->getCustomAttributes();
                                    @endphp
                                    @if(isset($attrs['capacity']))
                                        {{ $attrs['capacity'] }} kWh
                                    @elseif(isset($attrs['connector_type']))
                                        {{ $attrs['connector_type'] }}
                                    @elseif(isset($attrs['power_output']))
                                        {{ $attrs['power_output'] }} W
                                    @endif
                                </td>
                            @elseif($type === 'battery')
                                <td>{{ $product->getCustomAttributes()['capacity'] ?? '' }}</td>
                            @elseif($type === 'connector')
                                <td>{{ $product->getCustomAttributes()['connector_type'] ?? '' }}</td>
                            @elseif($type === 'solar_panel')
                                <td>{{ $product->getCustomAttributes()['power_output'] ?? '' }}</td>
                            @endif
                            <td>{{ Str::limit($product->description, 100) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $products->withQueryString()->links() }}
            </div>
        @else
            <div class="no-results">
                <p>No products found matching your filters.</p>
            </div>
        @endif
    </div>
</body>
</html>
