<table>
    <thead>
        <tr>
            <th>رقم الفاتورة</th>
            <th>تاريخ الفاتورة</th>
            <th>تاريخ الاستحقاق</th>
            <th>المنتج</th>
            <th>القسم</th>
            <th>الخصم</th>
            <th>نسبة الضريبة</th>
            <th>قيمة الضريبة</th>
            <th>الإجمالي</th>
            <th>الحالة</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $invoice)
        <tr>
            <td>
                {{ $invoice->invoice_number }}
            </td>
            <td>{{$invoice->invoice_Date}}</td>
            <td>{{$invoice->Due_date}}</td>
            <td>{{$invoice->product->product_name}}</td>
            <td>{{$invoice->product->section->section_name}}</td>
            <td>{{"$" . $invoice->Discount}}</td>
            <td>{{$invoice->Rate_VAT . "%"}}</td>
            <td>{{"$" . $invoice->Value_VAT}}</td>
            <td>{{"$" . $invoice->Total}}</td>
            <td>{{$invoice->status->status_name}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
