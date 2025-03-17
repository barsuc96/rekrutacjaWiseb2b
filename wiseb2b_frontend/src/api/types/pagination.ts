// standardowy typ paginacji w requeście
export interface IPaginationRequest {
  page?: number;
  limit?: number;
}

// standardowy typ paginacji w response
export interface IPaginationResponse<T> {
  total_count: number;
  total_pages: number;
  items: T[];
}
