<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Products', description: 'Product management endpoints')]
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/products',
        summary: 'List products',
        description: 'Returns all products with their related category.',
        operationId: 'products.index',
        tags: ['Products'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Products retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Products retrieved successfully'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(type: 'object')
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index()
    {
        //
        $products = Product::with('category')->get();
        return $this->successResponse('Products retrieved successfully', $products);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/products',
        summary: 'Create a new product',
        description: 'Creates a product and associates it to an existing category.',
        operationId: 'products.store',
        tags: ['Products'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'price', 'stock', 'category_id'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Gaming Mouse'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Wireless ergonomic mouse'),
                    new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0, example: 89.99),
                    new OA\Property(property: 'stock', type: 'integer', minimum: 0, example: 150),
                    new OA\Property(property: 'category_id', type: 'integer', example: 1),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product created successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Product created successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation Error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Validation Error'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);
        if($validator->fails()){
            return $this->errorResponse('Validation Error', 422, $validator->errors());
        }

        $product = Product::create($validator->validated());
        $product->load('category');

        return $this->successResponse('Product created successfully', $product);
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/products/{id}',
        summary: 'Get product by id',
        description: 'Returns a single product with its category.',
        operationId: 'products.show',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Product id',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Product retrieved successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Product not found'),
                        new OA\Property(property: 'data', nullable: true, example: null),
                    ]
                )
            ),
        ]
    )]
    public function show(string $id)
    {
        $product = Product::with('category')->find($id);
        if(!$product){
            return $this->errorResponse('Product not found', 404);
        }
        return $this->successResponse('Product retrieved successfully', $product);
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/products/{id}',
        summary: 'Update product',
        description: 'Updates one or more product fields.',
        operationId: 'products.update',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Product id',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Gaming Mouse Pro'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Updated description'),
                    new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0, example: 99.99),
                    new OA\Property(property: 'stock', type: 'integer', minimum: 0, example: 120),
                    new OA\Property(property: 'category_id', type: 'integer', example: 2),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product updated successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Product updated successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Product not found'),
                        new OA\Property(property: 'data', nullable: true, example: null),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation Error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Validation Error'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function update(Request $request, string $id)
    {
        //
        $product = Product::find($id);
        if(!$product){
            return $this->errorResponse('Product not found', 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);
        if($validator->fails()){
            return $this->errorResponse('Validation Error', 422, $validator->errors());
        }
        $product->update($request->all());
        return $this->successResponse('Product updated successfully', $product);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: '/products/{id}',
        summary: 'Delete product',
        description: 'Deletes a product by id.',
        operationId: 'products.destroy',
        tags: ['Products'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Product id',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product deleted successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Product deleted successfully'),
                        new OA\Property(property: 'data', nullable: true, example: null),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Product not found'),
                        new OA\Property(property: 'data', nullable: true, example: null),
                    ]
                )
            ),
        ]
    )]
    public function destroy(string $id)
    {
        //
        $product = Product::find($id);

        if(!$product){
            return $this->errorResponse('Product not found', 404);
        }
        $product->delete();
        return $this->successResponse('Product deleted successfully');
    }
}
