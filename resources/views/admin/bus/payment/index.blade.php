@extends('layouts.admin')
@section('contents')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('buses.payments.payment.processing') }}" method="POST" enctype="multipart/form-data" >
                        @csrf

                        <h1 class="text-center" style="font-weight: bold;">Booking Payment</h1>
                        <br>

                     
                        @if(Auth::user()->role_id == 4)
                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                        @endif

                        <div class="card outlined">
                            <div class="card-body">

                        
                                <div class="mb-3">
                                    <p class="mb-0 ml-1 text-left">
                                        Bus Gcash  # :
                                        <span class="text-danger">*</span>
                                    </p>
                                    <input type="text" name="refernceNumber" id="refernceNumber" class="form-control"
                                         placeholder="09000013213" readonly="" value="{{$bus_gcash_number}}">
                                    <input type="hidden" name="itemId" id="itemId" class="form-control"
                                       value="{{$id}}">
                                           <br>
                                    <p class="mb-0 ml-1 text-left">
                                        Passenger Name:
                                        <span class="text-danger">*</span>
                                    </p>
                                    <input type="text" name="passengerName" id="passengerName" class="form-control"
                                         placeholder="Passenger Name" value="{{$user_name}}" readonly="">
                                           <br>
                                    <p class="mb-0 ml-1 text-left">
                                        Enter Ref # :
                                        <span class="text-danger">*</span>
                                    </p>
                                    <input type="text" name="refernceNumber" id="refernceNumber" class="form-control"
                                         placeholder="Ref# " required>
                                           <br>
                                    

                                    <p class="mb-0 ml-1 text-left">
                                        Upload Proof  :
                                        <span class="text-danger">*</span>
                                    </p>

                                    <input type="file" name="refernceProof" id="refernceProof" class="form-control"
                                         placeholder="Refernce Proof"   onchange="changeImg(this)" required> 
                                    <div id="error" class="invalid-feedback" style="display:none">
                                        <strong>* Only png,jpg,jpeg allowed</strong>
                                    </div>    
                                     <br>
                                     <img id="blah" alt="your image" width="300" style="display:none;" height="400" />
 

                                  
                                    
                                </div>

                                 <div class="mb-3">
                                    <input type="checkbox" name="" value="yes">
                                    I here by that information is provided is authentic and i am responsible for legal actions. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. 
                                </div>
                            </div>
                        </div>

               
                        <div class="text-right">
                            <button class="btn btn-success" type="submit" id="save">
                                Save
                            </button>
                            <a class="btn btn-secondary" href="/" role="button">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
<script type="text/javascript">
    
 function changeImg(obj)
 {
 
   var file =  $("#refernceProof").val();
   var extension = file.replace(/^.*\./, '');

   if(extension == "png" || extension == "jpg" || extension == "jpeg")
   {
     $("#save").attr("disabled",false);
     document.getElementById('blah').style.display = "block";
     document.getElementById('error').style.display = "none";
     document.getElementById('blah').src = window.URL.createObjectURL(obj.files[0]);
   }else{
      document.getElementById('error').style.display = "block";
      $("#save").attr("disabled",true);
   }
   
 }
</script>
@section('css')
<style>
    .card.outlined {
        box-shadow: none;
        border: 1px solid #ccc;
    }

    #step-two-wrapper {
        max-height: 500px;
        border: 1px solid #ccc;
        overflow-y: auto;
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 5px;
    }

    #step-two-wrapper::-webkit-scrollbar {
        width: 10px;
        background-color: #fefefe;
        border-radius: 5px;
        box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    }

    #step-two-wrapper::-webkit-scrollbar-thumb {
        background-color: #ddd;
        border-radius: 5px;
    }

    #step-two-wrapper .card.active {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
</style>

@endsection

@section('js')

@endsection

@stop
