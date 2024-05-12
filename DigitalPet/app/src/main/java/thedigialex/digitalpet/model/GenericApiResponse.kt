package thedigialex.digitalpet.model

data class GenericApiResponse(
    val status: Boolean,
    val message: String,
    val errors: Map<String, List<String>>? = null
)
